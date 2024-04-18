<script>
import { defineComponent } from 'vue';
import Multiselect from '@vueform/multiselect';
import { Link } from '@inertiajs/inertia-vue3';

export default defineComponent({
    props: ['faq_category'],
    data: () => {
        return {

        }
    },
    components: {
        Multiselect,
        Link
    }
})
</script>

<template>
    <td>
        {{ faq_category?.title }}
    </td>
    <td>
        {{ faq_category?.artical }}
    </td>
    <td>
        <slot name="action"></slot>
    </td>
</template>
