<?php

namespace App\Http\Controllers;

use App\Models\DataTraining;
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




    public function index()
    {

        $title  = 'Data Training Page';
        $data = DataTraining::all();
        return view('data-training.index', compact('title', 'data'));
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


            return view('data-training.process', compact('title', 'result'));
        } catch (\Throwable $th) {
            return back()->with('failed', $th->getMessage());
        }
    }


    public function training($data)
    {

        $temp = [];
        $iteration = 6;
        $start = 0;
        while ($start < $iteration) {
            foreach ($data as $key => $value) {
                // if ($this->w1_choir != null  && $this->w1_soundman != null) {
                //     dd('mantap');
                // }
                if ($this->bias_choir === null && $this->bias_multimedia === null && $this->bias_soundman === null) {
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
                } elseif ($this->bias_choir !== null && $this->bias_multimedia !== null && $this->bias_soundman !== null) {

                    // if ($temp[$start] > 0) {

                    // }
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

        dd($temp);
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