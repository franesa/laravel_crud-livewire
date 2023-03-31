<?php

namespace App\Http\Livewire;

use App\Models\Post;
use Livewire\Component;

class Posts extends Component
{
  public $posts, $title, $body, $post_id;
  public $updateMode = false;

  public function render()
  {
    $this->posts = Post::latest()->get();
    return view('livewire.posts');
  }

  private function resetInputField()
  {
    $this->title = "";
    $this->body = "";
  }

  public function store()
  {
    $validateDate = $this->validate([
      "title" => "required",
      "body" => "required",
    ]);

    Post::create($validateDate);

    session()->flash("message", "Post Created Successfully.");

    $this->resetInputField();
  }

  public function edit($id)
  {
    $post = Post::findOrFail($id);
    $this->post_id = $id;
    $this->title = $post->title;
    $this->body = $post->body;

    $this->updateMode = true;
  }

  public function cancel()
  {
    $this->updateMode = false;
    $this->resetInputField();
  }

  public function update()
  {
    $validatedDate = $this->validate([
      'title' => 'required',
      'body' => 'required',
    ]);

    $post = Post::find($this->post_id);
    $post->update([
      "title" => $this->title,
      "body" => $this->body,
    ]);

    $this->updateMode = false;

    session()->flash("message", "Post Updated Successfully.");
    $this->resetInputField();
  }

  public function delete($id)
  {
    Post::find($id)->delete();
    session()->flash("message", "Post Deleted Successfully.");
  }
}
