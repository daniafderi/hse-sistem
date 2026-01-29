import Quill from 'quill';
import 'quill/dist/quill.snow.css';

export function initQuillEditors() {
  const editors = document.querySelectorAll('[id^="editor-"]');

  editors.forEach(editor => {
    const inputId = editor.id.replace('editor-', '');
    const hiddenInput = document.getElementById(inputId);

    if (hiddenInput) {
      const quill = new Quill(editor, {
        theme: 'snow',
        placeholder: editor.dataset.placeholder || 'Tulis sesuatu...',
        modules: {
          toolbar: [
              [{
                  header: [1, 2, !1]
              }],
              [{
                  font: []
              }],
              ["bold", "italic", "underline", "strike"],
              [{
                  size: ["small", !1, "large", "huge"]
              }],
              [{
                  list: "ordered"
              }, {
                  list: "bullet"
              }],
              [{
                  color: [],
                  background: [],
                  align: []
              }],
              ["link", "image", "code-block", "video"]
          ]
      }
      });

      // isi awal (jika ada)
      if (hiddenInput.value) {
        quill.root.innerHTML = hiddenInput.value;
      }

      // simpan ke input hidden saat form disubmit
      editor.closest('form').addEventListener('submit', () => {
        hiddenInput.value = quill.root.innerHTML;
      });
    }
  });
}
