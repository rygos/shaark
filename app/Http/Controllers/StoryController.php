<?php

namespace App\Http\Controllers;

use App\Story;
use Illuminate\Http\Request;

class StoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function create(Request $request)
    {
        return view('form-story')->with([
            'page_title' => 'Ajouter une story',
            'submit' => route('api.story.store'),
            'method' => 'POST',
        ]);
    }

    public function edit(Request $request, int $id)
    {
        $story = Story::with('post.tags')->findOrFail($id);

        return view('form-story')->with([
            'page_title' => 'Modifier une story',
            'submit' => route('api.story.update', $id),
            'method' => 'PUT',
            'story' => $story,
        ]);
    }

    public function delete(Request $request, int $id, string $hash)
    {
        if ($hash !== csrf_token()) {
            abort(403);
        }

        /** @var Story $link */
        $story = Story::with('post')->findOrFail($id);

        $story->post->delete();
        $story->delete();

        $this->flash(sprintf('La story "%s" a été supprimée !', $story->title), 'success');
        return redirect()->back();
    }
}