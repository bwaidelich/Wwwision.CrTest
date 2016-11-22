$(function() {
    $('.node-create-link').on('click', function(event) {
        event.preventDefault();
        $('#createNodeForm-workspaceId').val($(this).data('workspace-id'));
        if ($(this).data('reference-node-id')) {
            $('#createNodeForm-referenceNodeId').val($(this).data('reference-node-id'));
            $('#createNodeForm-position').val('after');
        }
        $('#createNodeModal').modal('show');
    });
    $('.node-move-link').on('click', function(event) {
        event.preventDefault();
        $('#moveNodeForm-workspaceId').val($(this).data('workspace-id'));
        $('#moveNodeForm-nodeId').val($(this).data('node-id'));
        $('#moveNodeForm-referenceNodeId option').show();
        $('#moveNodeForm-referenceNodeId option[value="' + $(this).data('node-id') + '"]').hide();
        $('#moveNodeModal').modal('show');
    });
    $('.newName').on('change', function() {
        $(this).get(0).form.submit();
    });

    $('time.timeago').timeago();
});