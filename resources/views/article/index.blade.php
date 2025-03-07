@if (session('status'))
    <div class="alert alert-success">
        {{ session('status') }}
    </div>
@endif
@extends('layouts.app')

@section('content')
    <h1>Список статей</h1>
    @foreach ($articles as $article)
        <div>
        <a href="{{ route('articles.show', $article->id) }}"> {{$article->name}} </a>
        {{-- Str::limit – функция-хелпер, которая обрезает текст до указанной длины --}}
        {{-- Используется для очень длинных текстов, которые нужно сократить --}}
        <div>{{Str::limit($article->body, 200)}}</div>
        <form action="{{ route('articles.destroy', $article->id) }}" method="POST" style="display: inline;">
            @csrf
            @method('DELETE')
            <button type="submit" onclick="return confirm('Вы уверены, что хотите удалить эту статью?')">Удалить</button>
        </form>
        <div>
        <p>
            @endforeach
@endsection
