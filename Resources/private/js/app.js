$(function () {
    var CodeMirrorInit = function (editor, elId, mode) {
        if (window[editor] instanceof CodeMirror) {
            window[editor].refresh();
        } else {
            window[editor] = CodeMirror.fromTextArea(document.getElementById(elId), {
                lineNumbers: true,
                indentWithTabs: false,
                mode: {name: mode, htmlMode: mode === 'twig'}
            });
        }
    };

    // bootstrap tab
    $(document).on('click', '[data-code-mirror]', function () {
        $(this).one('shown.bs.tab', function () {
            CodeMirrorInit(
                $(this).data('code-mirror'),
                $(this).data('code-mirror-id'),
                $(this).data('code-mirror-mode')
            );
        });
    });

    // semantic-ui
    $('.ui.tabular .code-mirror-tab').tab({
        'onVisible': function () {
            CodeMirrorInit(
                $(this).data('code-mirror'),
                $(this).data('code-mirror-id'),
                $(this).data('code-mirror-mode')
            );
        }
    });
});
