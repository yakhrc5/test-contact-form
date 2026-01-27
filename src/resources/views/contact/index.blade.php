@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection

@section('content')
<div class="contact-form__content">
  <div class="contact-form__heading">
      <h2>Contact</h2>
  </div>
  <form class="form" action="/contacts/confirm" method="post" >
    @csrf
    <div class="form__group">
      <div class="form__label">
          お名前<span class="form__label--item">※</span>
      </div>

      <div class="form__input name__input">
        <!-- 姓 -->
        <div class="name-field">
          <input type="text" name="last_name" placeholder="例:山田"
                  value="{{ old('last_name') }}" />
          @error('last_name')
            <div class="form__error">{{ $message }}</div>
          @enderror
        </div>
        <!-- 名 -->
        <div class="name-field">
          <input type="text" name="first_name" placeholder="例:太郎" 
                  value="{{ old('first_name') }}" />
          @error('first_name')
            <div class="form__error">{{ $message }}</div>
          @enderror
        </div> 
      </div>
    </div>   

    <div class="form__group">
      <div class="form__label">
        性別 <span class="form__required">※</span>
      </div>
      <div class="form__input gender-input">
        <label class="gender-option">
          <input type="radio" name="gender" value="1" {{ old('gender') == 1 ? 'checked' : '' }}>
            男性
        </label>
        <label class="gender-option">
          <input type="radio" name="gender" value="2" {{ old('gender') == 2 ? 'checked' : '' }}>
            女性
        </label>
        <label class="gender-option">
          <input type="radio" name="gender" value="3" {{ old('gender') == 3 ? 'checked' : '' }}>
          その他
        </label>
        @error('gender')
          <div class="form__error">{{ $message }}</div>
        @enderror
      </div>
    </div>    

    <div class="form__group">
      <div class="form__label">
          メールアドレス<span class="form__label--item">※</span>
      </div>
      <div class="form__group-content">
        <div class="form__input">
          <input type="email" name="email" placeholder="例:test@example.com" value="{{ old('email') }}" />
        </div>
        @error('email')
          <div class="form__error">{{ $message }}</div>
        @enderror
      </div>
    </div>
     
    <div class="form__group">
      <div class="form__label">
        電話番号<span class="form__required">※</span>
      </div>

      <div class="form__group-content">
        <div class="tel-row">
          <div class="tel-field">
            <input type="text" name="tel1" placeholder="080" value="{{ old('tel1') }}">
            @error('tel1')
              <div class="form__error">{{ $message }}</div>
            @enderror
          </div>

          <span class="tel-sep">-</span>

          <div class="tel-field">
            <input type="text" name="tel2" placeholder="1234" value="{{ old('tel2') }}">
            @error('tel2')
              <div class="form__error">{{ $message }}</div>
            @enderror
          </div>

          <span class="tel-sep">-</span>

          <div class="tel-field">
            <input type="text" name="tel3" placeholder="5678" value="{{ old('tel3') }}">
            @error('tel3')
              <div class="form__error">{{ $message }}</div>
            @enderror
          </div>
        </div>
      </div>
    </div>

    <div class="form__group">
      <div class="form__label">
          住所<span class="form__label--item">※</span>
      </div>
      <div class="form__group-content">
        <div class="form__input">
          <input type="text" name="address" placeholder="例:東京都渋谷区千駄ヶ谷1-2-3" value="{{ old('address') }}" />
        </div>
          @error('address')
            <div class="form__error">{{ $message }}</div>
          @enderror
      </div>
    </div>
    
    <div class="form__group">
      <div class="form__label">
          建物
      </div>
      <div class="form__group-content">
        <div class="form__input">
          <input type="text" name="building" placeholder="例:千駄ヶ谷マンション101" value="{{ old('building') }}" />
        </div>
      </div>
    </div>

    <div class="form__group">
      <div class="form__label">
          お問い合わせの種類<span class="form__label--item">※</span>
      </div>
      <div class="form__group-content">
        <div class="form__input">
          <select class="form__input-select" name="category_id">
            <option value="" disabled {{ old('category_id') ? '' : 'selected' }}>選択してください</option>
        @foreach ($categories as $category)
          <option value="{{ $category->id }}"
            {{ (string)old('category_id') === (string)$category->id ? 'selected' : '' }}>
            {{ $category->content }}
          </option>
        @endforeach
          </select>
        </div>
        @error('category_id')
          <div class="form__error">{{ $message }}</div>
        @enderror
      </div>
    </div>    

    <div class="form__group">
      <div class="form__group-title">
        お問い合わせ内容<span class="form__label--item">※</span>
      </div>
      <div class="form__group-content">
        <div class="form__input--textarea">
          <textarea name="detail" placeholder="お問い合わせ内容をご記載ください">{{ old('detail') }}</textarea>
        </div>
        @error('detail')
          <div class="form__error">{{ $message }}</div>
        @enderror
      </div>
    </div>
    <div class="form__button">
        <button class="form__button-submit" type="submit">送信</button>
    </div>
  </form>
</div>
@endsection