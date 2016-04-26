export function dragAndDropModule(VueInstance) {

    var  uploaderInstance = fineUploaderBasicInstance(VueInstance)

    return new fineUploader.DragAndDrop({
        dropZoneElements: [document.getElementById('fileView')],
        classes: {
            dropActive: 'blue'
        },
        callbacks: {
            processingDroppedFilesComplete: function (files, dropTarget) {
                uploaderInstance.addFiles(files);
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
                "Authorization": "Bearer " + societycms.jwtoken
            },
            params: {
                parent_uid: VueInstance.selectedParent
            }
        },
        callbacks: {

            onComplete: function (id, name, responseJSON) {
                VueInstance.fileUploadComplete(id, name, responseJSON)
            },
            onError: function (id, name, errorReason, XMLHttpRequest) {
                var responseJSON = JSON.parse(XMLHttpRequest.response);

                if (responseJSON.errors) {
                    toastr.error(responseJSON.errors[0], responseJSON.message);
                    this.editMode = null;
                    this.editObject = null;
                }

            },
            onUpload: function () {
                VueInstance.fileUploadStart();
            },
            onTotalProgress: function (totalUploadedBytes, totalBytes) {
                VueInstance.fileUploadTotalProgress(totalUploadedBytes, totalBytes);
            },
            onAllComplete: function (succeeded, failed) {
                VueInstance.fileUploadAllComplete(succeeded, failed);
            }
        }
    })
};