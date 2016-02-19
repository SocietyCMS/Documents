export function dragAndDropModule(VueInstance) {

    return new fineUploader.DragAndDrop({
        dropZoneElements: [document.getElementById('fileView')],
        classes: {
            dropActive: 'blue'
        },
        callbacks: {
            processingDroppedFilesComplete: function (files, dropTarget) {
                fineUploaderBasicInstance(VueInstance).addFiles(files);
            }
        }
    })

};

export function fineUploaderBasicInstance(VueInstance) {

    return new fineUploader.FineUploaderBasic({
        button: document.getElementById('uploadFileButton'),
        request: {
            endpoint: Vue.url(resourceDocumentsFileStore, {pool: VueInstance.$route.params.pool}),
            inputName: 'data-binary',
            customHeaders: {
                "Authorization": "Bearer "+jwtoken
            }
        },
        callbacks: {
            onComplete: function (id, name, responseJSON) {
                VueInstance.fileUploadComplete(id, name, responseJSON)
            },
            onError: function (id, name, errorReason, XMLHttpRequest) {
                var responseJSON = JSON.parse(XMLHttpRequest.response);

                if(responseJSON.errors) {
                    toastr.error(responseJSON.errors[0], responseJSON.message);
                    this.editMode = null;
                    this.editObject = null;
                }

            },
            onUpload: function () {
                VueInstance.fileUploadStart();
            },
            onTotalProgress: function (totalUploadedBytes, totalBytes) {
                $('#uploadFileProgrssbar').progress({
                    percent: Math.ceil(totalUploadedBytes / totalBytes * 100)
                });
            },
            onAllComplete: function (succeeded, failed) {
                VueInstance.fileUploadAllComplete(succeeded, failed);
            }
        }
    })
};