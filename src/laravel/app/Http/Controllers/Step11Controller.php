<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Step11Controller extends Controller
{
    public function index()
    {
        // PHPで用意したデータ → Bladeに渡す
        // このデータは「サーバー側（第1段階）」で処理される
        $serverMessage = 'こんにちは！PHPからBladeへのメッセージです。';

        $fruits = ['りんご', 'バナナ', 'みかん'];

        $isLoggedIn = true; // 仮のフラグ（ログイン状態のつもり）

        return view('step11', compact('serverMessage', 'fruits', 'isLoggedIn'));
    // 【compact()（コンパクト）とは？】
    // compact('serverMessage', 'fruits', 'isLoggedIn') は
    // ['serverMessage' => $serverMessage, 'fruits' => $fruits, 'isLoggedIn' => $isLoggedIn]
    // と同じ意味です。変数名をそのままキーにして配列を作ってくれます。
    }
}

