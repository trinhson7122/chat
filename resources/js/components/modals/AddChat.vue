<template>
    <!-- Modal -->
    <div class="modal fade" id="add-chat-modal" tabindex="-1" :aria-labelledby="id" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Thêm đoạn chat</h5>
                    <button type="button" class="btn-close close" data-bs-dismiss="modal" aria-label="Close">x</button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group mb-4">
                            <label>Name</label>
                            <input type="text" placeholder="Enter name"
                                class="form-control">
                            <!-- <div v-if="validationErrors.name" class="invalid-feedback">{{
                                validationErrors.name[0] }}
                            </div> -->
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link" data-bs-dismiss="modal">Đóng</button>
                    <button type="button" class="btn btn-primary">Thêm</button>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    props: {
        id: String,
    },
    data() {
        return {
            data: {
                name: '',
            },
            validationErrors: {},
        };
    },
};
</script>