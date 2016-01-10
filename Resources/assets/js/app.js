

Vue.filter('humanReadableFilesize', function (size) {
    if (size) {
        return filesize(size, {round: 0});
    }
    return '';
});

Vue.filter('semanticFileTypeClass', function (mime) {
    if (semanticFileTypeClassMap[mime]) {
        return semanticFileTypeClassMap[mime]
    }
    return "file outline"
});


