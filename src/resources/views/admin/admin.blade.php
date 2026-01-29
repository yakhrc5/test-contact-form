@extends('layouts.admin')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin.css') }}">
@endsection

@section('content')
<div class="admin">
  <main class="admin-main">
  <h2 class="admin__heading">Admin</h2>
      {{-- 検索エリア--}}
    <section class="admin-filter">
      <form class="filter" action="/search" method="GET">
        <input class="filter__input" type="text" name="name" placeholder="名前やメールアドレスを入力してください" value="{{ request('name') }}">
        <select class="filter__select" name="gender">
          <option value="">性別</option>
          <option value="all" {{ request('gender') == 'all' ? 'selected' : '' }}>全て</option>
          <option value="1" {{ request('gender') == '1' ? 'selected' : '' }}>男性</option>
          <option value="2" {{ request('gender') == '2' ? 'selected' : '' }}>女性</option>
          <option value="3" {{ request('gender') == '3' ? 'selected' : '' }}>その他</option>
        </select>
        <select class="filter__select" name="category_id">
          <option value="" {{ request('category_id') == "" ? 'selected' : '' }}>お問い合わせの種類</option>
          @foreach($categories as $category)
            <option value="{{ $category->id }}"
              {{ (string)request('category_id') === (string)$category->id ? 'selected' : '' }}>
              {{ $category->content }}
            </option>
          @endforeach
        </select>

        <input class="filter__date" type="date" name="date" value="{{ request('date') }}">
        <button class="filter__btn filter__btn--search" type="submit">検索</button>
        <a class="filter__btn filter__btn--reset" href="{{ url('/admin') }}">リセット</a>
      </form>
    </section>

    {{-- エクスポート + ページネーション--}}
    <section class="admin-toolbar">
      <a class="toolbar__export" href="{{ route('admin.export', request()->query()) }}" >
        エクスポート</a>

      <nav class="toolbar__pager" aria-label="pagination">
        {{ $contacts->onEachSide(2)->links() }}
      </nav>
    </section>

    {{-- テーブル --}}
    <section class="admin-table">
      <table class="table">
        <thead class="table__head">
          <tr>
            <th class="table__th">お名前</th>
            <th class="table__th">性別</th>
            <th class="table__th">メールアドレス</th>
            <th class="table__th">お問い合わせの種類</th>
            <th class="table__th table__th--action"></th>
          </tr>
        </thead>
        <tbody class="table__body">
          @foreach ($contacts as $contact)
            <tr class="table__tr">
              <td class="table__td">{{ $contact->last_name }} {{ $contact->first_name }}</td>
              @php
                  $genderLabel = match((int)$contact->gender){
                    1 => '男性',
                    2 => '女性',
                    3 => 'その他',
                    default => '',
                  };
                  @endphp
              <td class="table__td">{{ $genderLabel}}</td>
              <td class="table__td">{{ $contact['email'] }}</td>
              <td class="table__td">  {{ $contact->category->content ?? '' }} </td>
              <td class="table__td table__td--action">
              <button
                type="button"
                class="table__detail js-modal-open"
                data-id="{{ $contact->id }}"
                data-name="{{ $contact->last_name }} {{ $contact->first_name }}"
                data-gender="{{ match((int)$contact->gender){1=>'男性',2=>'女性',3=>'その他',default=>''} }}"
                data-email="{{ $contact->email }}"
                data-tel="{{ $contact->tel }}"
                data-address="{{ $contact->address }}"
                data-building="{{ $contact->building }}"
                data-category="{{ $contact->category->content ?? '' }}"
                data-detail="{{ $contact->detail }}"
              >
                詳細
              </button>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </section>
    {{-- ★ モーダル（部分view） --}}
    @include('admin._modal')
  </main>
</div>  
<script>
  (() => {
    const modal = document.getElementById('contact-modal');
    const deleteForm = document.getElementById('modal-delete-form');

    const setText = (key, value) => {
      const el = modal.querySelector(`[data-field="${key}"]`);
      if (!el) return;
      el.textContent = value ?? '';
    };

    const openModal = (btn) => {
      // 表示用
      setText('name', btn.dataset.name);
      setText('gender', btn.dataset.gender);
      setText('email', btn.dataset.email);
      setText('tel', btn.dataset.tel);
      setText('address', btn.dataset.address);
      setText('building', btn.dataset.building);
      setText('category', btn.dataset.category);
      setText('detail', btn.dataset.detail);

      // ★ 削除用（非表示）
      document.getElementById('modal-contact-id').value = btn.dataset.id;

      modal.classList.add('is-open');
    };

    const closeModal = () => {
      modal.classList.remove('is-open');
      modal.setAttribute('aria-hidden', 'true');
      document.body.style.overflow = '';
    };

    document.querySelectorAll('.js-modal-open').forEach(btn => {
      btn.addEventListener('click', () => openModal(btn));
    });

    modal.querySelectorAll('.js-modal-close').forEach(el => {
      el.addEventListener('click', closeModal);
    });

  })();
</script>

@endsection

