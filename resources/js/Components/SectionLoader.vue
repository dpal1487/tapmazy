<script>
import { defineComponent } from 'vue';

export default defineComponent({
    props: ["isLoading", "width", "height"]
})
</script>

<template>
    <div class="position-absolute w-100 h-100 d-flex align-items-center justify-content-center"
        style="top:0;left:0;right:0;bottom:0;">
        <div class="spinner-border" :style="`width: ${width}px; height: ${height}px;`" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>
</template>