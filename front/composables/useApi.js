export default function(request, opts = {}) {
    const config = useRuntimeConfig();
    const url = `${config.public.backendUrl}${request}`;

    opts.credentials = 'include';
    console.log(request);
    //const headers = useRequestHeaders(['cookie']);
    if (import.meta.server) {
      //headers = useRequestHeaders();
      opts.headers['cookie'] = useRequestHeaders(['cookie']);
    }
  
    return useFetch(() => url, opts);
}