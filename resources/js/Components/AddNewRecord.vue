<script>
import { defineComponent } from 'vue';
import { Link } from '@inertiajs/inertia-vue3';
export default defineComponent({
    props: {
        link: {
            type: String,
            default: "#",
        },
        name: {
            type: String,
            default: "Name",
        }
    },
    components: {
        Link
    }

})
</script>
<template>
    <div class="d-flex align-items-center gap-2 gap-lg-3">
        <Link :href="link" class="btn btn-sm fw-bold btn-primary">
        <i class="bi bi-plus-circle"></i> Add New {{ name }}
        </Link>
    </div>
</template>
