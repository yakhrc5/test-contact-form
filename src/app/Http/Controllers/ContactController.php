<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ContactRequest;
use App\Models\Category;
use App\Models\Contact;

class ContactController extends Controller
{
  public function index()
  {
    $contacts = Contact::with('category')->get();
    $categories = Category::all();

    return view('contact.index', compact('contacts', 'categories'));
  }

  public function confirm(ContactRequest $request)
  {
    $tel = implode('', [
      $request->tel1,
      $request->tel2,
      $request->tel3,
    ]);

    $fullName = implode(' ', [
      $request->last_name,
      $request->first_name,
    ]);

    $category = Category::find($request->category_id);

    $genderText = match ((int)$request->gender) {
      1 => '男性',
      2 => '女性',
      3 => 'その他',
      default => '',
    };

    $contact = [
      // 表示用
      'name'        => $fullName,
      'gender_text' => $genderText,

      // 修正・保存用（生データ）
      'gender'      => $request->gender,

      'email'       => $request->email,
      'tel'         => $tel,
      'address'     => $request->address,
      'building'    => $request->building,
      'category'    => $category->content ?? '',
      'detail'      => $request->detail,

      // 修正用（分割データ）
      'first_name'  => $request->first_name,
      'last_name'   => $request->last_name,
      'tel1'        => $request->tel1,
      'tel2'        => $request->tel2,
      'tel3'        => $request->tel3,
      'category_id' => $request->category_id,

    ];

    return view('contact.confirm',compact('contact'));

  }

  public function store(Request $request)
  {
    // 修正ボタンが押された場合
    if ($request->has('back')) {
      return redirect('/')
        ->withInput();
    }

    Contact::create([
      'first_name'  => $request->first_name,
      'last_name'   => $request->last_name,
      'gender'      => $request->gender,
      'email'       => $request->email,
      'tel'         => $request->tel,
      'address'     => $request->address,
      'building'    => $request->building,
      'category_id' => $request->category_id,
      'detail'      => $request->detail,
    ]);
    return view('contact.thanks');
  }



  
}
