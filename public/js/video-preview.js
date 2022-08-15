let previewTimeout = null;


jQuery(document).on('mouseenter', '.video_preview', function () {
    startPreview($(this));
    previewTimeout = setTimeout(stopPreview, 4000);
});

jQuery(document).on('mouseleave', '.video_preview', function () {
    clearTimeout(previewTimeout);
    previewTimeout = null;
    stopPreview($(this));
});

function startPreview(e) {
    e.get(0).currentTime = 1;
    e.get(0).playbackRate = 2.5;
    e.get(0).play();
}

function stopPreview(e) {
    e.get(0).currentTime = 0;
    e.get(0).playbackRate = 1;
    e.get(0).pause();
}
