@extends('layouts.app')

@section('title', 'ホームページ')

@section('content')
    <p>こんにちは、KOH！LaravelのBladeテンプレートが正しく動作しています！</p>

    {{-- @can（アットキャン）= admin-onlyゲートがOKの場合だけ表示する --}}
    @can('admin-only')
        <div style="margin-top: 16px; padding: 12px; background-color: #fef3c7; border: 1px solid #f59e0b; border-radius: 6px;">
            ⭐ 管理者メニュー（管理者だけに見えています）
        </div>
    @endcan

    {{-- @auth = ログインしている場合だけ処理する --}}
    {{-- @cannot = admin-onlyゲートがNGの場合だけ表示する --}}
    {{-- この2つを組み合わせることで「ログインしていて、かつ管理者でない人」だけに表示できる --}}
    @auth
        @cannot('admin-only')
            <div style="margin-top: 16px; padding: 12px; background-color: #f3f4f6; border: 1px solid #d1d5db; border-radius: 6px;">
                一般ユーザーとしてログインしています
            </div>
        @endcannot
    @endauth
@endsection