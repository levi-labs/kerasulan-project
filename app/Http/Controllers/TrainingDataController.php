<?php

namespace App\Http\Controllers;

use App\Models\DataTraining;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Reader\Xls\Color\BIFF5;

class TrainingDataController extends Controller
{
    public $net_choir = 0;
    public $net_multimedia = 0;
    public $net_soundman = 0;

    public $output_choir = null;
    public $output_multimedia = null;
    public $output_soundman = null;

    public $w1 = 0;
    public $w2 = 0;
    public $w3 = 0;
    public $bias = 0;
    public $rate = 0.1;

    public $error_choir = null;
    public $error_multimedia = null;
    public $error_soundman = null;

    public $w1_choir = null;
    public $w2_choir = null;
    public $w3_choir = null;
    public $bias_choir = null;

    public $w1_multimedia = null;
    public $w2_multimedia = null;
    public $w3_multimedia = null;
    public $bias_multimedia = null;

    public $w1_soundman = null;
    public $w2_soundman = null;
    public $w3_soundman = null;
    public $bias_soundman = null;
    public $result = [];




    public function index()
    {

        $title  = 'Data Training Page';
        $data = DataTraining::all();
        return view('training-data.index', compact('title', 'data'));
    }
    public function import(Request $request)
    {
        $file = $request->file('file');

        try {
            if (isset($file)) {
                DB::table('training_data')->truncate();

                Excel::import(new \App\Imports\DataTrainingImport, $file);

                return back()->with('success', 'Data Berhasil dimport');
            }

            return back()->with('failed', 'File not found');
        } catch (\Exception $e) {
            return back()->with('failed', $e->getMessage());
        }
    }
    public function process()
    {
        try {
            $title = 'Data Training';
            $sample = DB::table('training_data')
                ->select('nama', 'membaca_not_angka', 'mengoperasikan_software', 'mengoperasikan_audio', 'bidang')
                ->first();
            $data = DB::table('training_data')
                ->select('nama', 'membaca_not_angka', 'mengoperasikan_software', 'mengoperasikan_audio', 'bidang')
                ->get()
                ->toArray();


            $result = $this->training($data);


            return view('training-data.process', compact('title', 'result'));
        } catch (\Throwable $th) {
            return back()->with('failed', $th->getMessage());
        }
    }

    public function checkPredicted(Request $request)
    {
        $title = 'Data Training';

        $nama = $request->nama;
        $x1 = $request->not_angka;
        $x2 = $request->software;
        $x3 = $request->audio;

        $data = DB::table('training_data')
            ->select('nama', 'membaca_not_angka', 'mengoperasikan_software', 'mengoperasikan_audio', 'bidang')
            ->get()
            ->toArray();

        $result = $this->training($data);

        $sample = DB::table('predict_data')->latest('id')->first();

        try {
            $net_choir = $sample->w1_choir * $x1 + $sample->w2_choir * $x2 + $sample->w3_choir * $x3 + $sample->bias_choir;
            $net_multimedia = $sample->w1_multimedia * $x1 + $sample->w2_multimedia * $x2 + $sample->w3_multimedia * $x3 + $sample->bias_multimedia;
            $net_soundman = $sample->w1_soundman * $x1 + $sample->w2_soundman * $x2 + $sample->w3_soundman * $x3 + $sample->bias_soundman;
            $output_choir = $net_choir >= 0 ? 1 : 0;
            $output_multimedia = $net_multimedia >= 0 ? 1 : 0;
            $output_soundman = $net_soundman >= 0 ? 1 : 0;

            if ($x1 > $x2 && $x1 > $x3) {
                $target_choir = 1;
                $target_multimedia = 0;
                $target_soundman = 0;
            } elseif ($x2 > $x1 && $x2 > $x3) {
                $target_choir = 0;
                $target_multimedia = 1;
                $target_soundman = 0;
            } elseif ($x3 > $x1 && $x3 > $x2) {
                $target_choir = 0;
                $target_multimedia = 0;
                $target_soundman = 1;
            }
            $error_choir = $target_choir - $output_choir;
            $error_multimedia = $target_multimedia - $output_multimedia;
            $error_soundman = $target_soundman - $output_soundman;

            $w1_choir_baru = $sample->w1_choir + 0.1 * $error_choir * $x1;
            $w2_choir_baru = $sample->w2_choir + 0.1 * $error_choir * $x2;
            $w3_choir_baru = $sample->w3_choir + 0.1 * $error_choir * $x3;
            $w1_multimedia_baru = $sample->w1_multimedia + 0.1 * $error_multimedia * $x1;
            $w2_multimedia_baru = $sample->w2_multimedia + 0.1 * $error_multimedia * $x2;
            $w3_multimedia_baru = $sample->w3_multimedia + 0.1 * $error_multimedia * $x3;
            $w1_soundman_baru = $sample->w1_soundman + 0.1 * $error_soundman * $x1;
            $w2_soundman_baru = $sample->w2_soundman + 0.1 * $error_soundman * $x2;
            $w3_soundman_baru = $sample->w3_soundman + 0.1 * $error_soundman * $x3;
            $bias_choir_baru = $sample->bias_choir + 0.1 * $error_choir;
            $bias_multimedia_baru = $sample->bias_multimedia + 0.1 * $error_multimedia;
            $bias_soundman_baru = $sample->bias_soundman + 0.1 * $error_soundman;

            $predicted = [
                'nama' => $nama,
                'x1' => $x1,
                'x2' => $x2,
                'x3' => $x3,
                'net_choir' => $net_choir,
                'net_multimedia' => $net_multimedia,
                'net_soundman' => $net_soundman,
                'output_choir' => $output_choir,
                'output_multimedia' => $output_multimedia,
                'output_soundman' => $output_soundman,
                'target_choir' => $target_choir,
                'target_multimedia' => $target_multimedia,
                'target_soundman' => $target_soundman,
                'error_choir' => $error_choir,
                'error_multimedia' => $error_multimedia,
                'error_soundman' => $error_soundman,
                'w1_choir_baru' => $w1_choir_baru,
                'w2_choir_baru' => $w2_choir_baru,
                'w3_choir_baru' => $w3_choir_baru,
                'w1_multimedia_baru' => $w1_multimedia_baru,
                'w2_multimedia_baru' => $w2_multimedia_baru,
                'w3_multimedia_baru' => $w3_multimedia_baru,
                'w1_soundman_baru' => $w1_soundman_baru,
                'w2_soundman_baru' => $w2_soundman_baru,
                'w3_soundman_baru' => $w3_soundman_baru,
                'bias_choir_baru' => $bias_choir_baru,
                'bias_multimedia_baru' => $bias_multimedia_baru,
                'bias_soundman_baru' => $bias_soundman_baru
            ];

            $message = 'Data Sudah diproses';
            return view('training-data.process', compact('title', 'result', 'predicted', 'message'));
        } catch (\Throwable $th) {
            return back()->with('failed', $th->getMessage());
        }
    }


    public function training($data)
    {

        $temp = [];
        $iteration = 6;
        $start = 0;
        DB::table('predict_data')->truncate();
        while ($start < $iteration) {
            foreach ($data as $key => $value) {
                // if ($this->w1_choir != null  && $this->w1_soundman != null) {
                //     dd('mantap');
                // }
                if ($this->bias_choir === null && $this->bias_multimedia === null && $this->bias_soundman === null) {
                    $temp[$start][$key]['epoch']             = $start;
                    $temp[$start][$key]['nama']             = $value->nama;
                    $temp[$start][$key]['choir']            = $value->membaca_not_angka;
                    $temp[$start][$key]['multimedia']       = $value->mengoperasikan_software;
                    $temp[$start][$key]['soundman']         = $value->mengoperasikan_audio;

                    $temp[$start][$key]['net_choir']        = $value->membaca_not_angka * $this->w1 + $value->mengoperasikan_software * $this->w2 + $value->mengoperasikan_audio * $this->w3 + $this->bias;
                    $temp[$start][$key]['net_multimedia']   = $value->mengoperasikan_software * $this->w1 + $value->mengoperasikan_audio * $this->w2 + $value->membaca_not_angka * $this->w3 + $this->bias;
                    $temp[$start][$key]['net_soundman']     = $value->mengoperasikan_audio * $this->w1 + $value->mengoperasikan_software * $this->w2 + $value->membaca_not_angka * $this->w3 + $this->bias;




                    $temp[$start][$key]['output_choir']      = $temp[$start][$key]['net_choir'] >= 0 ? 1 : 0;
                    $temp[$start][$key]['output_multimedia'] = $temp[$start][$key]['net_multimedia'] >= 0 ? 1 : 0;
                    $temp[$start][$key]['output_soundman']   = $temp[$start][$key]['net_soundman'] >= 0 ? 1 : 0;

                    $temp[$start][$key]['target_choir']      = $value->bidang == 'choir' ? 1 : 0;
                    $temp[$start][$key]['target_multimedia'] = $value->bidang == 'multimedia' ? 1 : 0;
                    $temp[$start][$key]['target_soundman']   = $value->bidang == 'soundman' ? 1 : 0;

                    $temp[$start][$key]['error_choir']       = $temp[$start][$key]['target_choir'] - $temp[$start][$key]['output_choir'];
                    $temp[$start][$key]['error_multimedia']  = $temp[$start][$key]['target_multimedia'] - $temp[$start][$key]['output_multimedia'];
                    $temp[$start][$key]['error_soundman']    = $temp[$start][$key]['target_soundman'] - $temp[$start][$key]['output_soundman'];

                    $this->w1_choir = $this->w1 + $this->rate * $temp[$start][$key]['error_choir'] * $value->membaca_not_angka;;
                    $this->w2_choir = $this->w2 + $this->rate * $temp[$start][$key]['error_choir'] * $value->mengoperasikan_software;;
                    $this->w3_choir = $this->w3 + $this->rate * $temp[$start][$key]['error_choir'] * $value->mengoperasikan_audio;

                    $temp[$start][$key]['w1_baru_choir'] = $this->w1_choir;
                    $temp[$start][$key]['w2_baru_choir'] = $this->w2_choir;
                    $temp[$start][$key]['w3_baru_choir'] = $this->w3_choir;

                    // $temp_w1_choir[] = $temp[$key]['w1_baru_choir'];
                    // $temp_w2_choir[] = $temp[$key]['w2_baru_choir'];
                    // $temp_w3_choir[] = $temp[$key]['w3_baru_choir'];

                    $this->w1_multimedia = $this->w1 + $this->rate * $temp[$start][$key]['error_multimedia'] * $value->membaca_not_angka;
                    $this->w2_multimedia = $this->w2 + $this->rate * $temp[$start][$key]['error_multimedia'] * $value->mengoperasikan_software;
                    $this->w3_multimedia = $this->w3 + $this->rate * $temp[$start][$key]['error_multimedia'] * $value->mengoperasikan_audio;

                    $temp[$start][$key]['w1_baru_multimedia']   = $this->w1_multimedia;
                    $temp[$start][$key]['w2_baru_multimedia']   = $this->w2_multimedia;
                    $temp[$start][$key]['w3_baru_multimedia']   = $this->w3_multimedia;

                    // $temp_w1_multimedia[] = $temp[$key]['w1_baru_multimedia'];
                    // $temp_w2_multimedia[] = $temp[$key]['w2_baru_multimedia'];
                    // $temp_w3_multimedia[] = $temp[$key]['w3_baru_multimedia'];


                    $this->w1_soundman = $this->w1 + $this->rate * $temp[$start][$key]['error_soundman'] * $value->membaca_not_angka;
                    $this->w2_soundman = $this->w2 + $this->rate * $temp[$start][$key]['error_soundman'] * $value->mengoperasikan_software;
                    $this->w3_soundman = $this->w3 + $this->rate * $temp[$start][$key]['error_soundman'] * $value->mengoperasikan_audio;


                    $temp[$start][$key]['w1_baru_soundman']     = $this->w1_soundman;
                    $temp[$start][$key]['w2_baru_soundman']     = $this->w2_soundman;
                    $temp[$start][$key]['w3_baru_soundman']     = $this->w3_soundman;

                    // $temp_w1_soundman[] = $temp[$key]['w1_baru_soundman'];
                    // $temp_w2_soundman[] = $temp[$key]['w2_baru_soundman'];
                    // $temp_w3_soundman[] = $temp[$key]['w3_baru_soundman'];
                    $this->bias_choir                   = $this->bias + $this->rate * $temp[$start][$key]['error_choir'];
                    $this->bias_multimedia              = $this->bias + $this->rate * $temp[$start][$key]['error_multimedia'];
                    $this->bias_soundman                = $this->bias + $this->rate * $temp[$start][$key]['error_soundman'];


                    $temp[$start][$key]['bias_baru_choir']      = $this->bias_choir;
                    $temp[$start][$key]['bias_baru_multimedia'] = $this->bias_multimedia;
                    $temp[$start][$key]['bias_baru_soundman']   = $this->bias_soundman;

                    DB::table('predict_data')->insert([
                        'epoch' => $start,
                        'nama' => $value->nama,
                        'x1' => $value->membaca_not_angka,
                        'x2' => $value->mengoperasikan_software,
                        'x3' => $value->mengoperasikan_audio,
                        'net_choir' => $temp[$start][$key]['net_choir'],
                        'net_multimedia' => $temp[$start][$key]['net_multimedia'],
                        'net_soundman' => $temp[$start][$key]['net_soundman'],
                        'output_choir' => $temp[$start][$key]['output_choir'],
                        'output_multimedia' => $temp[$start][$key]['output_multimedia'],
                        'output_soundman' => $temp[$start][$key]['output_soundman'],
                        'target_choir' => $temp[$start][$key]['target_choir'],
                        'target_multimedia' => $temp[$start][$key]['target_multimedia'],
                        'target_soundman' => $temp[$start][$key]['target_soundman'],
                        'error_choir' => $temp[$start][$key]['error_choir'],
                        'error_multimedia' => $temp[$start][$key]['error_multimedia'],
                        'error_soundman' => $temp[$start][$key]['error_soundman'],
                        'w1_choir' => $this->w1_choir,
                        'w2_choir' => $this->w2_choir,
                        'w3_choir' => $this->w3_choir,
                        'w1_multimedia' => $this->w1_multimedia,
                        'w2_multimedia' => $this->w2_multimedia,
                        'w3_multimedia' => $this->w3_multimedia,
                        'w1_soundman' => $this->w1_soundman,
                        'w2_soundman' => $this->w2_soundman,
                        'w3_soundman' => $this->w3_soundman,
                        'bias_choir' => $this->bias_choir,
                        'bias_multimedia' => $this->bias_multimedia,
                        'bias_soundman' => $this->bias_soundman,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ]);
                } elseif ($this->bias_choir !== null && $this->bias_multimedia !== null && $this->bias_soundman !== null) {

                    // if ($temp[$start] > 0) {

                    // }
                    $temp[$start][$key]['epoch']                = $start;
                    $temp[$start][$key]['nama']                 = $value->nama;
                    $temp[$start][$key]['choir']                = $value->membaca_not_angka;
                    $temp[$start][$key]['multimedia']           = $value->mengoperasikan_software;
                    $temp[$start][$key]['soundman']             = $value->mengoperasikan_audio;

                    $temp[$start][$key]['net_choir']            = $temp[$start][$key]['choir'] * $this->w1_choir + $temp[$start][$key]['multimedia'] * $this->w2_choir + $temp[$start][$key]['soundman'] * $this->w3_choir + $this->bias_choir;
                    $temp[$start][$key]['net_multimedia']       = $temp[$start][$key]['choir'] * $this->w1_multimedia + $temp[$start][$key]['multimedia']  * $this->w2_multimedia +  $temp[$start][$key]['soundman'] * $this->w3_multimedia + $this->bias_multimedia;
                    $temp[$start][$key]['net_soundman']         = $temp[$start][$key]['choir'] * $this->w1_soundman + $temp[$start][$key]['multimedia']  * $this->w2_soundman +  $temp[$start][$key]['soundman'] * $this->w3_soundman + $this->bias_soundman;

                    $temp[$start][$key]['output_choir']         = $temp[$start][$key]['net_choir'] >= 0 ? 1 : 0;
                    $temp[$start][$key]['output_multimedia']    = $temp[$start][$key]['net_multimedia'] >= 0 ? 1 : 0;
                    $temp[$start][$key]['output_soundman']      = $temp[$start][$key]['net_soundman'] >= 0 ? 1 : 0;

                    $temp[$start][$key]['target_choir']         = $value->bidang == 'choir' ? 1 : 0;
                    $temp[$start][$key]['target_multimedia']    = $value->bidang == 'multimedia' ? 1 : 0;
                    $temp[$start][$key]['target_soundman']      = $value->bidang == 'soundman' ? 1 : 0;

                    $temp[$start][$key]['error_choir']          = $temp[$start][$key]['target_choir'] - $temp[$start][$key]['output_choir'];
                    $temp[$start][$key]['error_multimedia']     = $temp[$start][$key]['target_multimedia'] - $temp[$start][$key]['output_multimedia'];
                    $temp[$start][$key]['error_soundman']       = $temp[$start][$key]['target_soundman'] - $temp[$start][$key]['output_soundman'];

                    $this->w1_choir                             = $this->w1_choir + $this->rate * $temp[$start][$key]['error_choir'] * $value->membaca_not_angka;
                    $this->w2_choir                             = $this->w2_choir + $this->rate * $temp[$start][$key]['error_choir'] * $value->mengoperasikan_software;
                    $this->w3_choir                             = $this->w3_choir + $this->rate * $temp[$start][$key]['error_choir'] * $value->mengoperasikan_audio;

                    $temp[$start][$key]['w1_baru_choir']        = $this->w1_choir;
                    $temp[$start][$key]['w2_baru_choir']        = $this->w2_choir;
                    $temp[$start][$key]['w3_baru_choir']        = $this->w3_choir;

                    $this->w1_multimedia                        = $this->w1_multimedia + $this->rate * $temp[$start][$key]['error_multimedia'] * $value->membaca_not_angka;
                    $this->w2_multimedia                        = $this->w2_multimedia + $this->rate * $temp[$start][$key]['error_multimedia'] * $value->mengoperasikan_software;
                    $this->w3_multimedia                        = $this->w3_multimedia + $this->rate * $temp[$start][$key]['error_multimedia'] * $value->mengoperasikan_audio;

                    $temp[$start][$key]['w1_baru_multimedia']   = $this->w1_multimedia;
                    $temp[$start][$key]['w2_baru_multimedia']   = $this->w2_multimedia;
                    $temp[$start][$key]['w3_baru_multimedia']   = $this->w3_multimedia;

                    $this->w1_soundman                          = $this->w1_soundman + $this->rate * $temp[$start][$key]['error_soundman'] * $value->membaca_not_angka;
                    $this->w2_soundman                          = $this->w2_soundman + $this->rate * $temp[$start][$key]['error_soundman'] * $value->mengoperasikan_software;
                    $this->w3_soundman                          = $this->w3_soundman + $this->rate * $temp[$start][$key]['error_soundman'] * $value->mengoperasikan_audio;

                    $temp[$start][$key]['w1_baru_soundman']     = $this->w1_soundman;
                    $temp[$start][$key]['w2_baru_soundman']     = $this->w2_soundman;
                    $temp[$start][$key]['w3_baru_soundman']     = $this->w3_soundman;

                    $this->bias_choir                           = $this->bias_choir + $this->rate * $temp[$start][$key]['error_choir'];
                    $this->bias_multimedia                      = $this->bias_multimedia + $this->rate * $temp[$start][$key]['error_multimedia'];
                    $this->bias_soundman                        = $this->bias_soundman + $this->rate * $temp[$start][$key]['error_soundman'];

                    $temp[$start][$key]['bias_baru_choir']      = $this->bias_choir;
                    $temp[$start][$key]['bias_baru_multimedia'] = $this->bias_multimedia;
                    $temp[$start][$key]['bias_baru_soundman']   = $this->bias_soundman;

                    DB::table('predict_data')->insert([
                        'epoch' => $start,
                        'nama' =>  $value->nama,
                        'x1' => $value->membaca_not_angka,
                        'x2' => $value->mengoperasikan_software,
                        'x3' => $value->mengoperasikan_audio,
                        'net_choir' => $temp[$start][$key]['net_choir'],
                        'net_multimedia' => $temp[$start][$key]['net_multimedia'],
                        'net_soundman' => $temp[$start][$key]['net_soundman'],
                        'output_choir' => $temp[$start][$key]['output_choir'],
                        'output_multimedia' => $temp[$start][$key]['output_multimedia'],
                        'output_soundman' => $temp[$start][$key]['output_soundman'],
                        'target_choir' => $temp[$start][$key]['target_choir'],
                        'target_multimedia' => $temp[$start][$key]['target_multimedia'],
                        'target_soundman' => $temp[$start][$key]['target_soundman'],
                        'error_choir' => $temp[$start][$key]['error_choir'],
                        'error_multimedia' => $temp[$start][$key]['error_multimedia'],
                        'error_soundman' => $temp[$start][$key]['error_soundman'],
                        'w1_choir' => $this->w1_choir,
                        'w2_choir' => $this->w2_choir,
                        'w3_choir' => $this->w3_choir,
                        'w1_multimedia' => $this->w1_multimedia,
                        'w2_multimedia' => $this->w2_multimedia,
                        'w3_multimedia' => $this->w3_multimedia,
                        'w1_soundman' => $this->w1_soundman,
                        'w2_soundman' => $this->w2_soundman,
                        'w3_soundman' => $this->w3_soundman,
                        'bias_choir' => $this->bias_choir,
                        'bias_multimedia' => $this->bias_multimedia,
                        'bias_soundman' => $this->bias_soundman,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ]);



                    // $check_match =  array_filter($temp[$start], function ($value) {

                    //     return $value['target_choir'] !== $value['output_choir'] || $value['target_multimedia'] !== $value['output_multimedia'] || $value['target_soundman'] !== $value['output_soundman'];
                    // });

                    // if (count($check_match) > 0) {
                    //     $iteration++;
                    // }
                }
            }

            $start++;
        }
        // dd($check_match);
        $this->result = $temp;

        return $temp;
    }
}


// $temp_w_choir[$start][$key]['w1_baru_choir'] = $temp[$start][$key]['w1_baru_choir'];
// $temp_w_choir[$start][$key]['w2_baru_choir'] = $temp[$start][$key]['w2_baru_choir'];
// $temp_w_choir[$start][$key]['w3_baru_choir'] = $temp[$start][$key]['w3_baru_choir'];
// $temp_w_choir[$start][$key]['bias_baru_choir'] = $temp[$start][$key]['bias_baru_choir'];

// $temp_w_choir[$start][$key]['w1_baru_multimedia'] = $temp[$start][$key]['w1_baru_multimedia'];
// $temp_w_choir[$start][$key]['w2_baru_multimedia'] = $temp[$start][$key]['w2_baru_multimedia'];
// $temp_w_choir[$start][$key]['w3_baru_multimedia'] = $temp[$start][$key]['w3_baru_multimedia'];
// $temp_w_choir[$start][$key]['bias_baru_multimedia'] = $temp[$start][$key]['bias_baru_multimedia'];

// $temp_w_choir[$start][$key]['w1_baru_soundman'] = $temp[$start][$key]['w1_baru_soundman'];
// $temp_w_choir[$start][$key]['w2_baru_soundman'] = $temp[$start][$key]['w2_baru_soundman'];
// $temp_w_choir[$start][$key]['w3_baru_soundman'] = $temp[$start][$key]['w3_baru_soundman'];
// $temp_w_choir[$start][$key]['bias_baru_soundman'] = $temp[$start][$key]['bias_baru_soundman'];