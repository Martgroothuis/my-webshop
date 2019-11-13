<template>
  <div>
    <b-container>
      <b-card>
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
              <h4>Your Orders</h4>
            </div>
          </div>
        </div>
        <b-table
          responsive="sm"
          :items="orders"
          :fields="fields"
          :per-page="perPage"
          :current-page="currentPage"
          :busy="mute"
        >
          <template v-slot:cell(show_details)="row">
            <b-button
              size="sm"
              @click="row.toggleDetails"
              class="mr-2"
            >{{ row.detailsShowing ? 'Hide' : 'Show'}} Details</b-button>
          </template>
          <template v-slot:row-details="row">
            <b-list-group>
              <b-list-group-item>
                <div class="d-flex w-100 justify-content-between">
                  <h5 class="mb-1">Product in order</h5>
                  <small class="text-muted">{{ row.item.product_order.price }}</small>
                </div>
              </b-list-group-item>
              <b-list-group-item v-for="product in row.item.product_order" v-bind:key="product.id">
                <b-row>
                  <b-col>
                    <div v-if="product.product_version.image[0]">
                      <b-img-lazy
                        thumbnail
                        :src="'/images/small/' + product.product_version.image[0].url"
                        width="100"
                        fluid
                        alt="Responsive image"
                      ></b-img-lazy>
                    </div>
                    <div v-else>
                      <b-img-lazy
                        thumbnail
                        src="http://www.aof-clan.com/AoFWiki/images/6/60/No_Image_Available.png"
                        width="100"
                        fluid
                        alt="Responsive image"
                      ></b-img-lazy>
                    </div>
                  </b-col>
                  <b-col>
                    <ul>
                      <li>
                        <b>Price: {{ product.product_version.price }}</b>
                      </li>
                      <li>
                        <b>Ammount: {{ product.ammount }}</b>
                      </li>
                      <li>
                        <b>Color: {{ product.product_version.color.name }}</b>
                      </li>
                    </ul>
                  </b-col>
                </b-row>
              </b-list-group-item>
            </b-list-group>
          </template>
          <template v-slot:table-busy>
            <div class="text-center text-danger my-2">
              <b-spinner class="align-middle"></b-spinner>
              <strong>Loading...</strong>
            </div>
          </template>
        </b-table>
      </b-card>
    </b-container>
  </div>
</template>

<script>
function Order({ id, price, status, product_order, created_at }) {
  this.isActive = false;
  this.id = id;
  this.price = price;
  this.status = status;
  this.product_order = product_order;
  this.created_at = created_at;
}

export default {
  data() {
    return {
      mute: false,
      perPage: 10,
      currentPage: 1,
      orders: [],
      fields: [
        { key: "id", sortable: true },
        { key: "price", sortable: true },
        { key: "status", sortable: true },
        { key: "created_at", sortable: true },
        "show_details"
      ]
    };
  },
  methods: {
    read() {
      this.mute = true;
      window.axios.get("/api/orders").then(({ data }) => {
        console.log(data);
        this.orders = [];
        data.forEach(order => {
          this.orders.push(new Order(order));
        });
        this.mute = false;
      });
    }
  },
  computed: {
    rows() {
      return this.orders.length;
    }
  },
  created() {
    this.read();
  }
};
</script>
