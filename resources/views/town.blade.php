@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Поиск городов') }}
                        <form class="form theme-form" method="POST" action="{{ route('gettown') }}">
                            @csrf
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <input id="name" type="text"
                                               class="form-control @error('name') is-invalid @enderror" name="name"
                                               value="" placeholder="название города" required>
                                        <div class="help-block with-errors"></div>
                                        @error('name')
                                        <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary">{{ __('Наити')}}</button>
                                    </div>
                                </div>
                            </div>

                        </form>
                    </div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>

                        @endif
                        @if(count($listtown) !== 0)
                            <table class="table">
                                <thead>
                                <tr>
                                    <th scope="col">Название</th>
                                    <th scope="col">Широта</th>
                                    <th scope="col">Долгота</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($listtown as $town)
                                    <tr>
                                        <th>{{$town['name']}}</th>
                                        <td> {{$town['coordinates']['lon']}}</td>
                                        <td> {{$town['coordinates']['lat']}}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
