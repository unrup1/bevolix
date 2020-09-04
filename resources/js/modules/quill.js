import Vue from 'vue'
import VueQuillEditor from 'vue-quill-editor'

// Quill
Vue.use(VueQuillEditor, {
    modules: {
        toolbar: [
            [{ header: [] }],
            ['bold', 'italic', 'underline', 'link'],
            [{ color: [] }, { background: [] }],
            [{ list: 'ordered' }, { list: 'bullet' }],
            ['clean']
        ]
    },
    theme: 'snow'
});
