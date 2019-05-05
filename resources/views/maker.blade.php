@extends('layouts.simple')

@section('content')

    <main>
        @if (\Session::has('success'))
            <div class="alert">
                <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
                {!! \Session::get('success') !!}
            </div>
        @endif

        <div class="float-right bulker">
            <form id="myForm2" action="{{route('url.bulker')}}" method="POST" enctype="multipart/form-data">
                @csrf

                <div id="filer" class="divX file btn btn-lg btn-primary">
                    Subir Archivo
                    <input id="file" class="inputX" type="file" name="csv_file" accept=".csv"/>
                </div>
                <br>
                <small for="filer" style="color: white">* .csv </small>

            </form>
        </div>

        <div class="cntr">
            <div class="cntr-innr">


                <form id="myForm" action="{{route('url.maker')}}" method="POST">
                    @csrf

                    <label class="search" style="margin-top: 50px" for="inpt_search">
                        <input id="inpt_search" name="url" type="text" />
                    </label>
                    <p>Acortadora de urls.</p>
                </form>

            </div>
        </div>

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
                        <td><a class="black-linker" href="{{ route('url.search', ['url' => $url->shorty]) }}">{{$url->shorty}}</a></td>
                    </tr>
                @endforeach

            </tbody>
        </table>

        {{ $urls->links() }}

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

        p {
            margin-top: 30px;
        }
        .cntr {
            display: table;
            width: 100%;
            height: 100%;
        }
        .cntr .cntr-innr {
            display: table-cell;
            text-align: center;
            vertical-align: middle;
        }
        /*** STYLES ***/
        .search {
            display: inline-block;
            position: relative;
            height: 35px;
            width: 35px;
            box-sizing: border-box;
            margin: 0px 8px 7px 0px;
            padding: 7px 9px 0px 9px;
            border: 3px solid #ffffff;
            border-radius: 25px;
            transition: all 200ms ease;
            cursor: text;
        }
        .search:after {
            content: "";
            position: absolute;
            width: 3px;
            height: 20px;
            right: -5px;
            top: 21px;
            background: #ffffff;
            border-radius: 3px;
            transform: rotate(-45deg);
            transition: all 200ms ease;
        }
        .search.active,
        .search:hover {
            width: 200px;
            margin-right: 0px;
        }
        .search.active:after,
        .search:hover:after {
            height: 0px;
        }
        .search input {
            width: 100%;
            border: none;
            box-sizing: border-box;
            font-family: Helvetica;
            font-size: 15px;
            color: inherit;
            background: transparent;
            outline-width: 0px;
        }

        .linker{
            color: #000000;

        }

        .black-linker{
            color: white;
        }

        /* The alert message box */
        .alert {
            padding: 20px;
            background-color: #55f48c; /* Red */
            color: #000000;
            margin-bottom: 15px;
        }

        /* The close button */
        .closebtn {
            margin-left: 15px;
            color: white;
            font-weight: bold;
            float: right;
            font-size: 22px;
            line-height: 20px;
            cursor: pointer;
            transition: 0.3s;
        }

        /* When moving the mouse over the close button */
        .closebtn:hover {
            color: black;
        }

        .bulker{
            z-index: 9999;
            color: #000000;
            position: absolute;
            right: 5px;
            top: 5px;
        }

        .divX {
            position: relative;
            overflow: hidden;
        }

        .inputX {
            position: absolute;
            font-size: 50px;
            opacity: 0;
            right: 0;
            top: 0;
        }
    </style>

@endpush


@push('scripts')

    <script>
        $(document).ready(function () {

            $("#inpt_search").on('focus', function () {
                $(this).parent('label').addClass('active');
            });

            $("#inpt_search").on('blur', function () {
                if($(this).val().length == 0)
                    $(this).parent('label').removeClass('active');
            });

            $("#inpt_search").bind("keypress", {}, function keypressInBox(e) {
                var code = (e.keyCode ? e.keyCode : e.which);

                if (code == 13) {

                    e.preventDefault();

                    $('#myForm').submit();
                }
            });

            $("input:file").change(function (){

                $('#myForm2').submit();
            });

        });


    </script>

@endpush
