<script>
import { defineComponent } from 'vue';
import { toast } from 'vue3-toastify';
import Modal from '@/Components/Modal.vue';
import MappingForm from './MappingForm.vue'
export default defineComponent({
    props: ['project', 'isEdit', 'show', 'countries'],
    emits: ['hidemodal'],
    data() {
        return {
            form: {}
        }
    },
    components: {
        MappingForm,
        Modal
    },
    methods: {
        submit(form) {
            this.form = form;
            this.form
                .transform((data) => ({
                    ...data,
                }))
                .post(this.isEdit ? this.route("mapping.update", this.project.id) : this.route("mapping.store"), {
                    onSuccess: (data) => {
                        this.$emit('hidemodal')
                        toast.success(this.$page.props.jetstream.flash.message);
                    },
                    onError: (data) => {

                    },
                });
        },
    }

})
</script>
<template>
    <Modal :show="show" :title="isEdit ? 'Edit Link' : 'Add Link'" @onhide="$emit('hidemodal')">
        <mapping-form @submitted="submit" :project="project" :countries="countries">
            <template #action>
                <div class="d-flex align-items-center justify-content-end">
                    <button @click="$emit('hidemodal')" type="button" class="btn btn-secondary me-5">
                        Discard
                    </button>
                    <button type="submit" class="btn btn-primary" :class="{
                        'text-white-50':
                            form.processing,
                    }">
                        <div v-show="form.processing" class="spinner-border spinner-border-sm">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        Save Change
                    </button>
                </div>
            </template></mapping-form>
    </Modal>
</template>