@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        @if(Auth::user()->role_id == 2)
                            <form class="form theme-form" method="POST" action="{{ route('filterapplications') }}" >
                                @csrf
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <select name ='status'>
                                                <option value="new">new</option>
                                                <option value="work">in work</option>
                                                <option value="end">end</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-primary">{{ __('фильтровать')}}</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        @endif
                        @if(Auth::user()->role_id == 3)
                        <form class="form theme-form" method="POST" action="{{ route('createapplications') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <input id="text" type="text"
                                               class="form-control @error('text') is-invalid @enderror" name="text"
                                               value="{{ old('text') }}" placeholder="ваш текст" required>
                                        <div class="help-block with-errors"></div>
                                        @error('text')
                                        <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <input id="coordinates[lon]" type="text"
                                               class="form-control @error('coordinates[lon]') is-invalid @enderror" name="coordinates[lon]"
                                               value="{{ old('coordinates[lon]') }}" placeholder="Широта" required>
                                        <div class="help-block with-errors"></div>
                                        @error('coordinates[lon]')
                                        <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <input id="coordinates[lat]" type="text"
                                               class="form-control @error('coordinates[lat]') is-invalid @enderror" name="coordinates[lat]"
                                               value="{{ old('coordinates[lat]') }}" placeholder="Долгота" required>
                                        <div class="help-block with-errors"></div>
                                        @error('coordinates[lat]')
                                        <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <input type="file" class="form-control" name="docs[]" multiple />
                                        <div class="help-block with-errors"></div>
                                        @error('docs')
                                        <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary">{{ __('Отправить')}}</button>
                                    </div>
                                </div>
                            </div>

                        </form>
                        @endif
                    </div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>

                        @endif
                        @if(count($applications) !== 0)
                            <table class="table">
                                <thead>
                                <tr>
                                    <th scope="col">Название</th>
                                    <th scope="col">Широта</th>
                                    <th scope="col">Долгота</th>
                                    <th scope="col">Файлы</th>
                                    <th scope="col">Статус</th>
                                    @if(Auth::user()->role_id == 2)
                                        <th scope="col"></th>
                                    @endif
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($applications as $town)
                                    <tr>
                                        <th>{{$town['text']}}</th>
                                        <td> {{$town['coordinates']['lon']}}</td>
                                        <td> {{$town['coordinates']['lat']}}</td>
                                        <td>
                                            @foreach($town['user_file_url'] as $link)
                                                <a href="{{$link}}">{{Str::afterLast($link, '/')}}</a><br>
                                            @endforeach
                                        </td>
                                        <td>{{$town['status']}}</td>
                                        @if(Auth::user()->role_id == 2)
                                            <td>
                                                <div class="row">
                                                    <div class="col-sm-4">
                                                        <div class="form-group">
                                                            <a href="{{route('delapplications',['id'=>$town['id']])}}" type="button" class="btn btn-danger btn-sm">{{ __('Удалить')}}</a>

                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        @endif
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
