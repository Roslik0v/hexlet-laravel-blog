<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;

class ArticleController extends Controller
{
    // Показать все статьи с пагинацией
    public function index()
    {
        $articles = Article::paginate();  // Пагинация статей
        return view('article.index', compact('articles'));  // Отображение вьюшки с передачей статей
    }

    // Показать одну статью по ID
    public function show($id)
    {
        $article = Article::query()->findOrFail($id);  // Получение статьи по ID, если не найдено — выбросит 404
        return view('article.show', compact('article'));  // Отображение статьи
    }

    // Показать форму для создания новой статьи
    public function create()
    {
        $article = new Article();  // Создаем новый пустой объект Article
        return view('article.create', compact('article'));  // Отправляем объект в представление для формы
    }

    // Сохранить новую статью
    public function store(Request $request)
    {
        // Валидация данных формы
        $request->validate([
            'name' => 'required|unique:articles,name',  // Уникальность имени
            'body' => 'required|max:100',  // Контент должен быть обязательным и не более 100 символов
        ]);

        // Создание и заполнение нового объекта Article
        $article = new Article();
        $article->fill($request->all());  // Заполняем все поля с формы
        $article->save();  // Сохраняем статью

        // Сообщение об успешном сохранении
        $request->session()->flash('status', 'Task was successful!');

        // Перенаправление на главную страницу со списком статей
        return redirect()->route('articles.index');
    }

    // Показать форму для редактирования статьи
    public function edit($id)
    {
        $article = Article::findOrFail($id);  // Получаем статью для редактирования по ID
        return view('article.edit', compact('article'));  // Отправляем статью в форму редактирования
    }

    // Обновить статью
    public function update(Request $request, $id)
    {
        // Получение статьи для редактирования
        $article = Article::findOrFail($id);

        // Валидация обновленных данных
        $data = $request->validate([
            // Добавляем исключение для уникальности в проверке на имя статьи (позволяет редактировать без ошибки)
            'name' => "required|unique:articles,name,{$article->id}",
            'body' => 'required|max:100',  // Тело статьи также проверяется на максимальную длину
        ]);

        // Обновляем статью новыми данными
        $article->fill($data);
        $article->save();  // Сохраняем изменения

        // Сообщение об успешном обновлении
        $request->session()->flash('status', 'Update successful!');

        // Перенаправление на список статей
        return redirect()->route('articles.index');
    }

    // Удалить статью
    public function destroy(Request $request, $id)
    {
        // Находим статью по ID
        $article = Article::find($id);

        // Если статья существует — удаляем
        if ($article) {
            $article->delete();
        }

        // Сообщение об успешном удалении
        $request->session()->flash('status', 'Deleted!');

        // Перенаправление на список статей
        return redirect()->route('articles.index');
    }
}
