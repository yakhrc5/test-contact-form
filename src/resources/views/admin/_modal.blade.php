<div class="modal" id="contact-modal" aria-hidden="true">
  <div class="modal__overlay "></div>

  <div class="modal__panel" role="dialog" aria-modal="true">
    <button type="button" class="modal__close js-modal-close">×</button>

    <div class="modal__body">
      <dl class="modal__dl">
        <div class="modal__row"><dt>お名前</dt><dd data-field="name"></dd></div>
        <div class="modal__row"><dt>性別</dt><dd data-field="gender"></dd></div>
        <div class="modal__row"><dt>メールアドレス</dt><dd data-field="email"></dd></div>
        <div class="modal__row"><dt>電話番号</dt><dd data-field="tel"></dd></div>
        <div class="modal__row"><dt>住所</dt><dd data-field="address"></dd></div>
        <div class="modal__row"><dt>建物名</dt><dd data-field="building"></dd></div>
        <div class="modal__row"><dt>お問い合わせの種類</dt><dd data-field="category"></dd></div>
        <div class="modal__row"><dt>お問い合わせ内容</dt><dd data-field="detail" class="modal__detail"></dd></div>
      </dl>

      <form method="post" action="/delete" class="modal__delete">
        @csrf
        @method('DELETE')
        <input type="hidden" name="contact_id" id="modal-contact-id">
        <button type="submit" class="modal__delete-btn">削除</button>
      </form>
    </div>
  </div>
</div>
