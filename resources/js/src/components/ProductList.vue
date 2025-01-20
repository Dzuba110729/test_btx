<template>
    <div class="product-list">
        <table>
            <thead>
            <tr>
                <th>Артикул</th>
                <th>Название</th>
                <th>Статус</th>
                <th>Атрибуты</th>
            </tr>
            </thead>
            <tbody>
            <tr class="product-list-link" v-for="product in products" :key="product.id" @click.prevent="showProductPopup(product)">
                <td>{{ product.article }}</td>
                <td>{{ product.name }}</td>
                <td :class="statusClass(product.status)"> {{ product.status === 'available' ? 'Доступен' : 'Не доступен' }}</td>
                <td>
                    <span v-for="(attribute, index) in parseData(product.data)" :key="index">
                            {{ attribute.name }}: {{ attribute.value }}<br />
                    </span>
                </td>
            </tr>
            </tbody>
        </table>
        <ProductPopup
            v-if="selectedProduct"
            :product="selectedProduct"
            :isAdmin="isAdmin"
            @close="selectedProduct = null"
            @productUpdated="handleProductUpdated"
        />
    </div>
</template>

<script>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import ProductCard from './ProductCard.vue';
import ProductPopup from './PopupForms/ProductPopup.vue';

export default {
    components: {
        ProductCard,
        ProductPopup
    },
    props: {
        products: {
            type: Array,
            required: true
        },
        isAdmin: Boolean,
    },
    setup(props, { emit }) {
        const selectedProduct = ref(null);

        const showProductPopup = (product) => {
            selectedProduct.value = product;
        };

        const parseData = (data) => {
            try {
                return JSON.parse(data);
            } catch (e) {
                return {};
            }
        };

        const fetchProducts = async () => {
            try {
                const response = await axios.get('/api/products');
                emit('productUpdated', response.data.data);
            } catch (error) {
                console.error('Error fetching products:', error);
            }
        };

        onMounted(fetchProducts);

        const statusClass = (status) => {
            return status === 'available' ? 'status-available' : 'status-unavailable';
        };

        const handleProductUpdated = () => {
            fetchProducts();
        };

        const editProduct = (product) => {
            selectedProduct.value = product;
        };

        const deleteProduct = (productId) => {
            axios
                .delete(`/api/products/${productId}`)
                .then(() => {
                    emit('productUpdated');
                })
                .catch((error) => {
                    console.error('Ошибка при удалении продукта:', error);
                });
        };

        return {
            selectedProduct,
            showProductPopup,
            statusClass,
            parseData,
            handleProductUpdated,
            editProduct,
            deleteProduct
        };
    }
};
</script>

<style scoped>
table {
    width: 100%;
    border-collapse: collapse;
}

th, td {
    text-align: left;
    min-width: 150px;
    width: 100%;
    height: 30px;
    padding-left: 20px;
}

th {
    background-color: inherit;
    text-transform: uppercase;
    font-size: 9px;
    line-height: 11px;
    color: #6E6E6F;
}

.product-list-link {
    cursor: pointer;
}

tbody {
    background-color: #fff;
}

tbody tr {
    border-top: 1px solid #C4C4C4;
    border-bottom: 1px solid #C4C4C4;
}
tbody td {
    padding-top: 16px;
    padding-bottom: 16px;
    font-size: 11px;
    line-height: 11px;
}
</style>
