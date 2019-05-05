@extends('layouts.simple')

@section('content')

    <main>

        <table class="table" style="margin-top: 40px">
            <thead>
            <tr>
                <th scope="col">URL</th>
                <th scope="col">Shorty</th>
            </tr>
            </thead>
            <tbody>

            @foreach($urls as $url)
                <tr>
                    <td>{{$url->url}}</td>
                    <td><a class="black-linker" @if($url->shorty !== "NOT_VALID_URL") href="{{ route('url.search', ['url' => $url->shorty]) }}" @endif>{{$url->shorty}}</a></td>
                </tr>
            @endforeach

            </tbody>
        </table>

        <div class="row col" style="text-align: right">
            <button id="boton" class="btn btn-primary">Descargar</button>
        </div>

        <form id="pathToSend" action="{{route('url.download')}}" method="POST">
            @csrf

            <input type="hidden" name="path" value="{{$path}}">
        </form>
    </main>

@endsection

@push('styles')

    <style>
        html,
        body {
            background-color: #3c4491;
        }
        body {
            background: #3c4491;
            color: #ffffff;
        }

    </style>

@endpush


@push('scripts')

    <script>
        $(document).ready(function () {

            $('#boton').on('click', function (e) {
                e.preventDefault();

                $('#pathToSend').submit();
            });


        });

    </script>

@endpush
