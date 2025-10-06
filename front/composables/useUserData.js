export default function() {
    const userData = useState('userData', () => (
      {
        id: 0,
        name: '',
        email: '',
        isLoggedIn: false,
        isAdmin: false
      }
    ));
  
    const setUserData = (values) => {
      Object.keys(values).forEach(function (key) {
        if (key in userData.value) {
          userData.value[key] = values[key];
        }
      })
    };

    const logout = () => {

      const { data } = useApi('api/logout')

      const status = data.value.status;

      console.log(status);

          setUserData({
            id: undefined,
            name: undefined,
            email: undefined,
            isLoggedIn: false,
          }); 
};
  
    return {
      userData,
      setUserData,
      logout,
    };
  }