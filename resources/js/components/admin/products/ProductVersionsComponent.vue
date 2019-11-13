<template>
  <div v-if="this.product">
    <div class="d-flex justify-content-between mb-4">
      <div class="d-flex justify-content-start">
        <div>
          <b-pagination
            class="mb-0"
            v-model="currentPage"
            :total-rows="rows"
            :per-page="perPage"
            aria-controls="my-table"
          ></b-pagination>
        </div>
        <div class="m-1 ml-3">
          <h4>Product: {{this.product.name}}</h4>
        </div>
      </div>

      <b-button variant="primary" v-b-modal.newproductversion>New Product Version</b-button>
      <b-modal
        id="newproductversion"
        ref="newproductversion"
        title="New Product Version"
        hide-footer
      >
        <form method="post" @submit.prevent="create()">
          <input type="hidden" name="_token" :value="csrf" />
          <b-form-group id="input-group-price" label="Price:" label-for="price">
            <b-form-input
              id="price"
              step="any"
              v-model="form.price"
              type="number"
              required
              placeholder="Enter price"
            ></b-form-input>
          </b-form-group>
          <b-form-group id="input-group-color" label="Color:" label-for="color">
            <b-form-input autocomplete="off" v-model="form.color" list="my-list-id"></b-form-input>

            <datalist id="my-list-id">
              <option
                v-for="color in colors"
                :value="color.id"
                v-bind:key="color.id"
              >{{ color.name }}</option>
            </datalist>
          </b-form-group>

          <b-button block type="submit" variant="primary">Submit</b-button>
        </form>
      </b-modal>
    </div>

    <b-table
      striped
      hover
      outline
      responsive="sm"
      :items="products"
      :fields="fields"
      :per-page="perPage"
      :current-page="currentPage"
      :busy="mute"
    >
      <template v-slot:cell(actions)="row">
        <b-button size="sm" variant="danger" @click="del(row.item.id)" class="mr-2">Delete</b-button>

        <b-button
          size="sm"
          variant="info"
          @click="selectProductVersion(row.item.id)"
          class="mr-2"
        >Select</b-button>

        <b-button
          size="sm"
          variant="primary"
          @click="edit(row.item.id, row.item.price, row.item.color, row.item.name)"
          class="mr-2"
        >Edit</b-button>
      </template>

      <template v-slot:table-busy>
        <div class="text-center text-danger my-2">
          <b-spinner class="align-middle"></b-spinner>
          <strong>Loading...</strong>
        </div>
      </template>
    </b-table>
    <b-modal
      id="editproductversion"
      ref="editproductversion"
      title="Edit Product version"
      hide-footer
    >
      <form method="post" @submit.prevent="update(selectedProduct.id, form.price, form.color)">
        <input type="hidden" name="_token" :value="csrf" />
        <b-form-group id="input-group-price" label="Price:" label-for="price">
          <b-form-input
            id="price"
            v-model="form.price"
            step="any"
            type="number"
            placeholder="Enter price"
          ></b-form-input>
        </b-form-group>
        <b-form-group id="input-group-color" label="Color:" label-for="color">
          <b-form-input autocomplete="off" v-bind="form.color" id="color" list="my-list-id"></b-form-input>

          <datalist id="my-list-id">
            <option v-for="color in colors" :value="color.id" v-bind:key="color.id">{{ color.name }}</option>
          </datalist>
        </b-form-group>

        <b-button block type="submit" variant="primary">Submit</b-button>
      </form>
    </b-modal>
  </div>
  <div v-else>
    <h3>Select a product first.</h3>
  </div>
</template>

<script>
function ProductVersion({ id, price, color_id }) {
  this.id = id;
  this.price = price;
  this.color = color_id;
}

export default {
  props: ["product"],
  data() {
    return {
      csrf: document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute("content"),

      mute: false,
      perPage: 10,
      currentPage: 1,

      form: [],
      fields: [
        {
          key: "id",
          sortable: true
        },
        {
          key: "price",
          sortable: true
        },
        {
          key: "color",
          sortable: true
        },
        "actions"
      ],
      products: [],
      selectedProduct: [],
      colors: []
    };
  },
  methods: {
    selectProductVersion(id) {
      this.$emit("selectProductVersion", id);
    },
    create() {
      console.log(this.price);
      this.mute = true;
      this.$refs["newproductversion"].hide();

      window.axios
        .post("/api/productversions", {
          price: this.form.price,
          product_id: this.product.id,
          color_id: this.form.color
        })
        .then(({ data }) => {
          console.log(data);
          this.$emit("toast", "success", "Create", "Product Version Created");

          this.products.push(new ProductVersion(data));
          this.mute = false;
        });
    },
    read() {
      this.mute = true;
      this.products = [];
      window.axios
        .get(`/api/productversions/${this.product.id}`)
        .then(({ data }) => {
          console.log(data);
          data.forEach(product => {
            this.products.push(new ProductVersion(product));
          });
          this.mute = false;
        });
      window.axios.get(`/api/colors`).then(({ data }) => {
        this.colors = data;
        // data.forEach(product => {
        //   this.products.push(new Color(color));
        // });
        this.mute = false;
      });
    },
    edit(id, price, color, name) {
      this.selectedProduct.id = id;
      this.form.price = price;
      this.form.color = color;
      this.selectedProduct.name = name;
      console.log(this.form.price);

      // console.log(this.selectedProduct);
      this.$refs["editproductversion"].show();
    },
    update(id, price, color) {
      this.$refs["editproductversion"].hide();

      console.log(price);
      console.log(id);
      console.log(color);

      window.axios
        .put(`/api/productversions/${id}`, {
          price: price
        })
        .then(() => {
          this.$emit(
            "toast",
            "warning",
            "Update",
            "Product Version: " + name + " Updated"
          );

          console.log(this.products.find(product => product.id === id));

          this.products.find(product => product.id === id).price = price;
        });
    },
    del(id) {
      this.mute = true;
      window.axios.delete(`/api/productversions/${id}`).then(() => {
        this.$emit("toast", "danger", "Deletion", "Product Version:  Deleted");

        let index = this.products.findIndex(product => product.id === id);
        this.products.splice(index, 1);
        this.mute = false;
      });
    }
  },
  watch: {
    product: function(newVal, oldVal) {
      console.log("Prop changed: ", newVal, " | was: ", oldVal);
      this.product = newVal;
      this.read();
    }
  },
  computed: {
    rows() {
      return this.products.length;
    }
  }
  //   created() {
  //     this.read();
  //   }
};
</script>