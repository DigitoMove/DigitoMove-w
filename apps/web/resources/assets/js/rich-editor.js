'use strict';
document.addEventListener('DOMContentLoaded', function () {
  const editor = document.querySelector('.rich-editor');
  const input = document.querySelector('.rich-editor-input');
  if (!editor || !input) return;
  input.value = editor.innerHTML;
  editor.addEventListener('input', function () { input.value = editor.innerHTML; });
  document.querySelectorAll('[data-command]').forEach(button => button.addEventListener('click', function () {
    const command = button.dataset.command;
    let value = button.dataset.value || null;
    if (command === 'createLink') value = window.prompt('Link URL');
    if (value !== null || command !== 'createLink') document.execCommand(command, false, value);
    editor.focus();
  }));
  editor.closest('form').addEventListener('submit', function () { input.value = editor.innerHTML; });
});
