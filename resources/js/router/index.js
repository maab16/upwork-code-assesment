import Vue from 'vue'
import VueRouter from 'vue-router'
import routes from './routes.js'

Vue.use(VueRouter)

// The middleware for every page of the application.
// const globalMiddleware = ['locale', 'check-auth']

// Load middleware modules dynamically.
// const routeMiddleware = resolveMiddleware(
//   require.context('~/middleware', false, /.*\.js$/)
// )

const router = createRouter()

// sync(store, router)

export default router

/**
 * Create a new router instance.
 *
 * @return {VueRouter}
 */
function createRouter () {
  const router = new VueRouter({
    // scrollBehavior,
    mode: 'history',
    routes
  })

//   router.beforeEach(beforeEach)
//   router.afterEach(afterEach)

  return router
}