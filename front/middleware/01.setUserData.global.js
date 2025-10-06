
export default defineNuxtRouteMiddleware(async(route, from) => {

    const { setUserData } = useUserData();
    // const { getNavItemByRoute } = useNavItems();
    // const { setPageData } = usePageData();

    // const nuxtApp = useNuxtApp();
    // if (import.meta.client && nuxtApp.isHydrating && nuxtApp.payload.serverRendered) return;

    // const isStatic = /\.(jpg|jpeg|gif|png|css|js|ico|xml|rss|txt|svg|webp)$/.test(route.path);
    // if(isStatic)    return;

    // const currentNavItem = getNavItemByRoute(route.path);
    // setPageData({
    //     title: currentNavItem.title,
    //     icon: currentNavItem.icon,
    // });


    const { data } = await useApi('/api/userdata' , {
        method: 'GET',
    });

    console.log('user data', data);

    if (data.value && data.value.id) {
        setUserData({
            name: data.value.name,
            id: data.value.id,
            email: data.value.email,
            isLoggedIn: true,
        });
    }   else {
        setUserData({
            name: '',
            id: 0,
            email: '',
            isLoggedIn: false,
        });
    }
});