<?php

use Illuminate\Support\Facades\DB;


function getPerceptron($data)
{
    try {

        $perceptron = [];
        // dd($data);

        $latest_row =  DB::table('predict_data')->latest('id')->first();
        // dd($latest_row);
        $rate = 0.1;
        foreach ($data as $key => $value) {
            $perceptron[$key]['nama']           = $value->nama;
            $perceptron[$key]['x1'] = $value->membaca_not_angka;
            $perceptron[$key]['x2'] = $value->mengoperasikan_software;
            $perceptron[$key]['x3'] = $value->mengoperasikan_audio;
            $perceptron[$key]['net_choir']      = $value->membaca_not_angka * $latest_row->w1_choir + $value->mengoperasikan_software * $latest_row->w2_choir + $value->mengoperasikan_audio * $latest_row->w3_choir + $latest_row->bias_choir;
            $perceptron[$key]['net_multimedia'] = $value->membaca_not_angka * $latest_row->w1_multimedia + $value->mengoperasikan_software * $latest_row->w2_multimedia + $value->mengoperasikan_audio * $latest_row->w3_multimedia + $latest_row->bias_multimedia;
            $perceptron[$key]['net_soundman']   = $value->membaca_not_angka * $latest_row->w1_soundman + $value->mengoperasikan_software * $latest_row->w2_soundman + $value->mengoperasikan_audio * $latest_row->w3_soundman + $latest_row->bias_soundman;


            $perceptron[$key]['output_choir']       = $perceptron[$key]['net_choir'] >= 0 ? 1 : 0;
            $perceptron[$key]['output_multimedia']  = $perceptron[$key]['net_multimedia'] >= 0 ? 1 : 0;
            $perceptron[$key]['output_soundman']    = $perceptron[$key]['net_soundman'] >= 0 ? 1 : 0;

            if ($value->membaca_not_angka > $value->mengoperasikan_software && $value->membaca_not_angka > $value->mengoperasikan_audio) {
                $perceptron[$key]['target_choir'] = 1;
                $perceptron[$key]['target_multimedia'] = 0;
                $perceptron[$key]['target_soundman'] = 0;
            } elseif ($value->mengoperasikan_software > $value->membaca_not_angka && $value->mengoperasikan_software > $value->mengoperasikan_audio) {
                $perceptron[$key]['target_choir'] = 0;
                $perceptron[$key]['target_multimedia'] = 1;
                $perceptron[$key]['target_soundman'] = 0;
            } elseif ($value->mengoperasikan_audio > $value->membaca_not_angka && $value->mengoperasikan_audio > $value->mengoperasikan_software) {
                $perceptron[$key]['target_choir'] = 0;
                $perceptron[$key]['target_multimedia'] = 0;
                $perceptron[$key]['target_soundman'] = 1;
            }
            $perceptron[$key]['error_choir'] = $perceptron[$key]['target_choir'] - $perceptron[$key]['output_choir'];
            $perceptron[$key]['error_multimedia'] = $perceptron[$key]['target_multimedia'] - $perceptron[$key]['output_multimedia'];
            $perceptron[$key]['error_soundman'] = $perceptron[$key]['target_soundman'] - $perceptron[$key]['output_soundman'];

            $perceptron[$key]['w1_choir'] = $latest_row->w1_choir + $rate * $perceptron[$key]['error_choir'] * $value->membaca_not_angka;
            $perceptron[$key]['w2_choir'] = $latest_row->w2_choir + $rate * $perceptron[$key]['error_choir'] * $value->mengoperasikan_software;
            $perceptron[$key]['w3_choir'] = $latest_row->w3_choir + $rate * $perceptron[$key]['error_choir'] * $value->mengoperasikan_audio;


            $perceptron[$key]['w1_multimedia'] = $latest_row->w1_multimedia + $rate * $perceptron[$key]['error_multimedia'] * $value->membaca_not_angka;
            $perceptron[$key]['w2_multimedia'] = $latest_row->w2_multimedia + $rate * $perceptron[$key]['error_multimedia'] * $value->mengoperasikan_software;
            $perceptron[$key]['w3_multimedia'] = $latest_row->w2_multimedia + $rate * $perceptron[$key]['error_multimedia'] * $value->mengoperasikan_audio;


            $perceptron[$key]['w1_soundman'] = $latest_row->w1_soundman + $rate * $perceptron[$key]['error_soundman'] * $value->membaca_not_angka;
            $perceptron[$key]['w2_soundman'] = $latest_row->w2_soundman + $rate * $perceptron[$key]['error_soundman'] * $value->mengoperasikan_software;
            $perceptron[$key]['w3_soundman'] = $latest_row->w3_soundman + $rate * $perceptron[$key]['error_soundman'] * $value->mengoperasikan_audio;

            $perceptron[$key]['bias_choir'] = $latest_row->bias_choir + $rate * $perceptron[$key]['error_choir'];
            $perceptron[$key]['bias_multimedia'] = $latest_row->bias_multimedia + $rate * $perceptron[$key]['error_multimedia'];
            $perceptron[$key]['bias_soundman'] = $latest_row->bias_soundman + $rate * $perceptron[$key]['error_soundman'];
        }

        return $perceptron;
    } catch (\Exception $e) {
        return $e->getMessage();
    }
}



function getResult($data)
{
    try {
        $rate = 1;
        $treshold = 0;
        $predicted = [];
        $latest_row =  DB::table('predicted')->latest('id')->first();
        $w1_baru = null;
        $w2_baru = null;
        $delta_w1 = null;
        $delta_w2 = null;
        $delta_w1_current = [];
        $delta_w2_current = [];

        // $v[$key] = $value->pecah_suara * $w1_baru + $value->audio_video * $w2_baru;
        // $w1_baru = $w1_baru + $rate * $error * $value->pecah_suara;
        // $v = $x1 * $latest_row->w1_baru + $x2 * $latest_row->w2_baru;

        foreach ($data as $key => $value) {
            $v[$key] = $value->pecah_suara * $latest_row->w1_baru + $value->audio_video * $latest_row->w2_baru;

            if ($v[$key] < $treshold) {

                $y_luaran = 0;
                $y_target = $value->bidang == 'Choir' ? 1 : 0;
                $error = $y_target - $y_luaran;


                if ($w1_baru == null) {
                    $w1_baru = $latest_row->w1_baru + $rate * $latest_row->error * $value->pecah_suara;
                    $w2_baru = $latest_row->w2_baru + $rate * $latest_row->error * $value->audio_video;
                } elseif ($w1_baru != null) {

                    $w1_baru = $w1_baru + $rate * $error * $value->pecah_suara;
                    $w2_baru = $w2_baru + $rate * $error * $value->audio_video;
                }
                $n_w1[$key] = $w1_baru;

                if ($delta_w1 === null && $delta_w2 === null) {
                    $delta_w1 = $w1_baru - $latest_row->w1_baru;
                    $delta_w2 = $w2_baru - $latest_row->delta_w2;
                } else if (count($delta_w1_current) > 0 && count($delta_w2_current) > 0) {
                    $delta_w1 = $n_w1[$key] - $n_w1[$key - 1];
                    $delta_w2 = $w2_baru - $delta_w2;
                }
                $delta_w1_current[$key] = $delta_w1;
                $delta_w2_current[$key] = $delta_w2;

                $predicted[$key] = [
                    'nama' => $value->nama,
                    'x1' => $value->pecah_suara,
                    'x2' => $value->audio_video,
                    'v' => $v[$key],
                    'y_luaran' => $y_luaran,
                    'y_target' => $y_target,
                    'error' => $error,
                    'w1_baru' => $w1_baru,
                    'w2_baru' => $w2_baru,
                    'delta_w1' => $delta_w1,
                    'delta_w2' => $delta_w2
                ];
            } else {

                $y_luaran = 1;
                $y_target = $value->bidang == 'Choir' ? 1 : 0;
                $error = $y_target - $y_luaran;


                if ($w1_baru == null) {
                    $w1_baru = $latest_row->w1_baru + $rate * $latest_row->error * $value->pecah_suara;
                    $w2_baru = $latest_row->w2_baru + $rate * $latest_row->error * $value->audio_video;
                } elseif ($w1_baru != null) {

                    $w1_baru = $w1_baru + $rate * $error * $value->pecah_suara;
                    $w2_baru = $w2_baru + $rate * $error * $value->audio_video;
                }
                $n_w1[$key] = $w1_baru;

                if ($delta_w1 == null && $delta_w2 == null) {
                    $delta_w1 = $w1_baru - $latest_row->w1_baru;
                    $delta_w2 = $w2_baru - $latest_row->delta_w2;
                } else if (count($delta_w1_current) > 0 && count($delta_w2_current) > 0) {
                    $delta_w1 = $n_w1[$key] - $n_w1[$key - 1];
                    $delta_w2 = $w2_baru - $delta_w2;
                }
                $delta_w1_current[$key] = $delta_w1;
                $delta_w2_current[$key] = $delta_w2;

                $predicted[$key] = [
                    'nama' => $value->nama,
                    'x1' => $value->pecah_suara,
                    'x2' => $value->audio_video,
                    'v' => $v[$key],
                    'y_luaran' => $y_luaran,
                    'y_target' => $y_target,
                    'error' => $error,
                    'w1_baru' => $w1_baru,
                    'w2_baru' => $w2_baru,
                    'delta_w1' => $delta_w1,
                    'delta_w2' => $delta_w2
                ];
            }
        }
        // dd($predicted, $n_w1, $delta_w1_current, $delta_w2_current);
        return $predicted;
    } catch (\Exception $e) {
        return $e->getMessage();
    }
}
