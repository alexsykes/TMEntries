{{--<script src="https://cdn.tiny.cloud/1/musi1wv2kebgt1z2g4j9gymziz98ctscmixwe5hc7s2x5ve9/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>--}}
{{--<script>--}}
{{--    tinymce.init({--}}
{{--        selector: 'textarea.withEditor', // Replace this CSS selector to match the placeholder element for TinyMCE--}}
{{--        plugins: 'code table lists link',--}}
{{--        toolbar: 'undo redo | blocks | bold italic | alignleft aligncenter alignright | indent outdent | bullist numlist | link',--}}
{{--        convert_urls: false,--}}
{{--    });--}}
{{--</script>--}}

<!-- Place the first <script> tag in your HTML's <head> -->
<script src="https://cdn.tiny.cloud/1/musi1wv2kebgt1z2g4j9gymziz98ctscmixwe5hc7s2x5ve9/tinymce/8/tinymce.min.js" referrerpolicy="origin" crossorigin="anonymous"></script>

<!-- Place the following <script> and <textarea> tags your HTML's <body> -->
<script>
    tinymce.init({
        selector: 'textarea.withEditor',
        plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
        toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | image media table | align lineheight | link numlist bullist indent outdent | emoticons charmap | removeformat',
        link_assume_external_targets: true,
        relative_urls : false,
        remove_script_host : false,
        convert_urls : true,
    });
</script>

{{--<script src="{{ asset('public/js/tinymce/tinymce.min.js') }}" referrerpolicy="origin"></script>--}}
{{--<script>--}}
{{--    tinymce.init({--}}
{{--        selector: 'textarea#notes', // Replace this CSS selector to match the placeholder element for TinyMCE--}}
{{--        plugins: 'code table lists',--}}
{{--        toolbar: 'undo redo | blocks | bold italic | alignleft aligncenter alignright | indent outdent | bullist numlist'--}}
{{--    });--}}
{{--</script>--}}
