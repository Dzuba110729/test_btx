<template>
    <div class="product-card">
        <h3>{{ product.name }}</h3>
        <p>{{ product.article }}</p>
        <p>Status: {{ product.status }}</p>
        <p v-if="product.data.Color">Color: {{ product.data.Color }}</p>
        <p v-if="product.data.Size">Size: {{ product.data.Size }}</p>
    </div>
</template>

<script>
export default {
    props: {
        product: Object
    }
};
</script>
