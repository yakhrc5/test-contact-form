<?php

namespace App\Http\Controllers;

use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Http\Request;
use App\Models\Contact;
use App\Models\Category;

class AdminController extends Controller
{
  public function admin(Request $request)
  {
    $query = Contact::with('category')
      ->orderByDesc('id');

    // 名前
    if ($request->filled('name')) {
      $input = $request->name;

      // スペース除去（半角・全角・連続もまとめて除去）
      $nospace = preg_replace('/\s+/u', '', $input);

      $query->where(function ($q) use ($input, $nospace) {
        $q->where('first_name', 'like', "%{$input}%")
          ->orWhere('last_name', 'like', "%{$input}%")

          // ★メールも追加
          ->orWhere('email', 'like', "%{$input}%")
          
          // 連結（スペースなし）
          ->orWhereRaw(
            "CONCAT_WS('', last_name, first_name) LIKE ?",
            ["%{$nospace}%"]
          )

          // 連結（半角スペース）
          ->orWhereRaw(
            "CONCAT_WS(' ', last_name, first_name) LIKE ?",
            ["%{$input}%"]
          )

          // 連結（全角スペース）
          ->orWhereRaw(
            "CONCAT_WS('　', last_name, first_name) LIKE ?",
            ["%{$input}%"]
          );
      });
    }

    // 性別
    if ($request->filled('gender') && $request->gender !== 'all') {
      $query->where('gender', $request->gender);
    }
    
    // 種類
    if ($request->filled('category_id')) {
      $query->where('category_id', $request->category_id);
    }

    // 日付
    if ($request->filled('date')) {
      $query->whereDate('created_at', $request->date);
    }

    // ★ 最後に取得
    $contacts = $query->paginate(7)->appends($request->query());

    $categories = Category::all();

    return view('admin.admin', compact('contacts', 'categories'));
  }

  public function destroy(Request $request)
  {
    $contact = Contact::findOrFail($request->contact_id);
    $contact->delete();

    return  back();

  }
  public function export(Request $request): StreamedResponse
  {
    $query = Contact::with('category')->orderByDesc('id');

    // 名前＋メール検索）
    if ($request->filled('name')) {
      $input = $request->name;
      $nospace = preg_replace('/\s+/u', '', $input);

      $query->where(function ($q) use ($input, $nospace) {
        $q->where('first_name', 'like', "%{$input}%")
          ->orWhere('last_name', 'like', "%{$input}%")
          ->orWhere('email', 'like', "%{$input}%")
          ->orWhereRaw("CONCAT_WS('', last_name, first_name) LIKE ?", ["%{$nospace}%"])
          ->orWhereRaw("CONCAT_WS(' ', last_name, first_name) LIKE ?", ["%{$input}%"])
          ->orWhereRaw("CONCAT_WS('　', last_name, first_name) LIKE ?", ["%{$input}%"]);
      });
    }

    // 性別
    if ($request->filled('gender')) {
      $query->where('gender', $request->gender);
    }

    // 種類
    if ($request->filled('category_id')) {
      $query->where('category_id', $request->category_id);
    }

    // 日付
    if ($request->filled('date')) {
      $query->whereDate('created_at', $request->date);
    }

    // ファイル名（任意）
    $filename = 'contacts_' . now()->format('Ymd_His') . '.csv';

    return response()->streamDownload(function () use ($query) {
      $handle = fopen('php://output', 'w');

      // Excel文字化け対策（UTF-8 BOM）
      fwrite($handle, "\xEF\xBB\xBF");

      // ヘッダー行
      fputcsv($handle, [
        'ID',
        '姓',
        '名',
        '性別',
        'メール',
        '電話番号',
        '住所',
        '建物名',
        'お問い合わせの種類',
        'お問い合わせの内容',
        '登録日',
      ]);

      // 大量でも落ちないように chunk
      $query->chunk(200, function ($contacts) use ($handle) {
        foreach ($contacts as $c) {
          $genderLabel = match ((int)$c->gender) {
            1 => '男性',
            2 => '女性',
            3 => 'その他',
            default => '',
          };

          fputcsv($handle, [
            $c->id,
            $c->last_name,
            $c->first_name,
            $genderLabel,
            $c->email,
            $c->tel,
            $c->address,
            $c->building,
            $c->category->content ?? '',
            $c->detail,
            optional($c->created_at)->format('Y-m-d'),
          ]);
        }
      });

      fclose($handle);
    }, $filename, [
      'Content-Type' => 'text/csv; charset=UTF-8',
    ]);
  }
}
