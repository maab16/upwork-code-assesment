function page (path) {
  return () => import(/* webpackChunkName: '' */ `../pages/${path}`).then(m => m.default || m)
}

export default [
    {
      path: '/products',
      component: page('products/index.vue'),
      name: "product"
    },
    {
      path: '/products/create',
      component: page('products/create.vue'),
      name: "product.create"
    },
    {
      path: '/products/:id/edit',
      component: page('products/edit.vue'),
      name: "product.edit",
      props: true
    },
  ]