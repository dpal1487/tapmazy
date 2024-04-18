<script>
import { defineComponent } from 'vue';
import { Link } from '@inertiajs/inertia-vue3';

export default defineComponent({
    props: {
        link: {
            type: String,
            default: '#'
        },
        routeProp: {
            type: String,
            default: null
        },
        processing: {
            type: Boolean,
            default: 'off'
        }
    },
    components: {
        Link
    }

})
</script>
<template>
    <div class="col-12">
        <div class="d-flex justify-content-end gap-5">
            <Link :href="link" class="btn btn-outline-secondary d-flex align-items-center justify-content-center">
            Discard
            </Link>
            <button type="submit" class="btn btn-primary align-items-center justify-content-center"
                :data-kt-indicator="processing ? 'on' : 'off'">
                <span class="indicator-label">
                    <span v-if="routeProp == 'plan.edit'">Save Changes</span>
                    <span v-if="routeProp == 'plan.create'">Save</span>
                </span>
                <span class="indicator-progress">
                    Please wait... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                </span>
            </button>
        </div>
    </div>
</template>
