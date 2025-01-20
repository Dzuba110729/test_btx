<template>
    <div v-if="product" class="popup-overlay">
        <div class="popup-content">
            <div class="popup-header">
                <div class="popup-header-title">
                    {{ isEditing ? `Редактировать ${product.article}/${product.name}` : `${product.article}/${product.name}` }}
                </div>
                <button class="close-btn" @click="$emit('close')"></button>
            </div>
            <form v-if="isEditing" @submit.prevent="saveChanges">
                <div class="form-group">
                    <label for="article">Артикул</label>
                    <input type="text" id="article" v-model="editedProduct.article" :disabled="!isAdmin" required />
                </div>
                <div class="form-group">
                    <label for="name">Название</label>
                    <input type="text" id="name" v-model="editedProduct.name" required />
                </div>
                <div class="form-group">
                    <label for="status">Статус</label>
                    <select v-model="editedProduct.status" required>
                        <option value="available">Доступен</option>
                        <option value="unavailable">Не доступен</option>
                    </select>
                </div>
                <div class="form-group">
                    <div class="form-group-title">Атрибуты</div>
                    <div v-for="(attribute, index) in editedProduct.attributes" :key="index" class="attribute-row">
                        <div class="attribute-block">
                            <label for="name">Название</label>
                            <input
                                v-model="attribute.name"
                                type="text"
                                class="attribute-name"
                                required
                            />
                        </div>
                        <div class="attribute-block">
                            <label for="value">Значение</label>
                            <input
                                v-model="attribute.value"
                                type="text"
                                class="attribute-value"
                                required
                            />
                        </div>
                        <button type="button" @click="removeAttribute(index)" class="remove-attribute"></button>
                    </div>
                    <button class="form-group-attribute-btn" type="button" @click="addAttribute">Добавить атрибут</button>
                </div>
                <div class="form-actions">
                    <button class="form-actions-btn" type="submit">Сохранить</button>
                </div>
            </form>
            <div v-else class="popup-content-product">
                <div class="popup-content-product-name">
                    <p>Артикул</p>
                    <p>Название</p>
                    <p>Статус</p>
                    <p>Атрибуты</p>
                </div>
                <div class="popup-content-product-text">
                    <p> {{ product.article || "Нет данных" }}</p>
                    <p> {{ product.name || "Нет данных" }}</p>
                    <p> {{ statusLabel(product.status) }}</p>
                    <p> {{ parseAttributesForDisplay(product.data) }}</p>
                </div>
            </div>
            <div class="popup-actions">
                <button class="popup-actions-red" v-if="!isEditing" @click="toggleEdit"></button>
                <button class="popup-actions-delet" v-if="!isEditing" @click="deleteProduct"></button>
            </div>
        </div>
    </div>
</template>

<script>
import axios from "axios";

export default {
    props: {
        product: {
            type: Object,
            default: () => ({}),
        },
        isAdmin: Boolean,
    },
    data() {
        return {
            isEditing: false,
            editedProduct: {},
        };
    },
    watch: {
        product: {
            immediate: true,
            handler(newProduct) {
                if (newProduct) {
                    this.editedProduct = {
                        ...newProduct,
                        attributes: this.parseAttributes(newProduct.data),
                    };
                    console.log("editedProduct updated:", this.editedProduct);
                }
            },
        },
        isAdmin(newVal) {
            console.log('isAdmin changed:', newVal);
        },
    },
    methods: {
        statusLabel(status) {
            return status === "available" ? "Доступен" : "Не доступен";
        },

        parseAttributesForDisplay(data) {
            if (!data) return 'Нет данных';
            try {
                const attributes = JSON.parse(data);
                return attributes
                    .map(attr => `${attr.name}: ${attr.value}`)
                    .join(', ');
            } catch (error) {
                console.error("Ошибка парсинга атрибутов:", error);
                return 'Некорректные данные';
            }
        },

        parseAttributes(data) {
            if (!data) return [];
            try {
                return JSON.parse(data);
            } catch (error) {
                console.error("Ошибка парсинга атрибутов:", error);
                return [];
            }
        },
        addAttribute() {
            this.editedProduct.attributes.push({ name: "", value: "" });
        },
        removeAttribute(index) {
            this.editedProduct.attributes.splice(index, 1);
        },
        toggleEdit() {
            this.isEditing = true;
        },
        cancelEdit() {
            this.isEditing = false;
            this.editedProduct = {
                ...this.product,
                attributes: this.parseAttributes(this.product.data),
            };
        },
        saveChanges() {
            const updatedProduct = {
                ...this.editedProduct,
                data: JSON.stringify(this.editedProduct.attributes),
            };

            axios
                .put(`/api/products/${this.product.id}`, updatedProduct)
                .then((response) => {
                    this.$emit("productUpdated", updatedProduct);
                    this.isEditing = false;
                    this.$emit('close');
                })
                .catch((error) => {
                    console.error("Ошибка при сохранении изменений:", error);
                });
        },
        deleteProduct() {
            axios
                .delete(`/api/products/${this.product.id}`)
                .then(() => {
                    this.$emit("productUpdated");
                    this.$emit("close");
                })
                .catch((error) => {
                    console.error("Ошибка при удалении продукта:", error);
                });
        },
    },
};
</script>


<style scoped>
.popup-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: center;
}

.popup-content {
    background: #374050;
    padding: 27px 12px;
    border-radius: 5px;
    width: 630px;
    min-height: 390px;
    position: relative;
    color: #fff;
}

.popup-content form {
    color: #000;
}

.popup-content-product {
    margin-top: 30px;
    display: flex;
    gap: 20px;
    font-size: 11px;
    line-height: 11px;
}

.popup-content-product-text {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.popup-content-product-name {
    color: #FFFFFFB2;
    width: 80px;
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.popup-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.popup-header-title {
    font-size: 20px;
    font-weight: 700;
    line-height: 11px;
    color: #fff;
    margin-right: 50px;
}

.close-btn {
    width: 30px;
    height: 30px;
    cursor: pointer;
    background-image: url("/public/images/Close_round.svg");
    background-position: center;
    background-size: cover;
}

.popup-actions {
    position: absolute;
    top: 32px;
    right: 55px;
}

.popup-actions-red {
    background-image: url("/public/images/red.svg");
    background-position: center;
    background-size: cover;
    background-color: #00000066;
    width: 20px;
    height: 20px;
}

.popup-actions-delet {
    background-image: url("/public/images/delet.svg");
    background-position: center;
    background-size: cover;
    background-color: #00000066;
    width: 20px;
    height: 20px;
}

form {
    margin-top: 20px;
}

.form-group {
    margin-bottom: 13px;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    font-size: 9px;
    line-height: 11px;
    color: #fff;
}

.form-group input,
.form-group select {
    width: 472px;
    height: 30px;
    padding: 8px;
    border-radius: 5px;
    font-size: 11px;
    line-height: 11px;
    font-weight: 300;
}

input:focus,
select:focus {
    outline: none;
}

select {
    cursor: pointer;
}

.form-group-title {
    font-size: 14px;
    line-height: 11px;
    font-weight: 700;
    color: #fff;
    margin-bottom: 13px;
}

.form-group-attribute-btn {
    margin-top: 13px;
    color: #5FC6F1;
    border-bottom: 0.5px dashed #5FC6F1;
    font-size: 9px;
    line-height: 11px;
    padding-left: 10px;
    position: relative;
}

.form-group-attribute-btn::after {
    content: '+';
    position: absolute;
    left: 0;
    top: 0;
    font-size: 9px;
    color: #5FC6F1;
}

.form-group .attribute-row {
    display: flex;
    justify-content: space-between;
    gap: 10px;
    align-items: center;
    width: 472px;
}

.attribute-row:nth-child(n + 2) {
    margin-top: 13px;
}

.attribute-block {
    width: 236px;
}

.attribute-block input {
    width: 100%;
}

.remove-attribute {
    background-image: url("/public/images/Vector (Stroke).svg");
    background-size: cover;
    background-position: center;
    width: 10px;
    height: 11px;
    margin-top: 20px;
    cursor: pointer;
}

.form-actions {
    margin-top: 21px;
    width: 140px;
    height: 30px;
    border-radius: 5px;
    text-align: center;
    background-color: #5FC6F1;
}

.form-actions-btn {
    width: 100%;
    height: 100%;
    font-size: 11px;
    line-height: 11px;
    color: #fff;
}
</style>

