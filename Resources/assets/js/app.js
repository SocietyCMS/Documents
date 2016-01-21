Vue.filter('humanReadableFilesize', function (size) {
    return filesize(size, {round: 0});
});

Vue.filter('semanticFileTypeClass', function (mime) {
    if (semanticFileTypeClassMap[mime]) {
        return semanticFileTypeClassMap[mime]
    }
    return "file outline"
});


