export const formatErrorMessages = (error) => {
    const data = error.response?.data;
    if (data && data.errors) {
        return Object.values(data.errors).flat().join('; ');
    } else {
        return error.response?.data?.message || 'Lỗi xảy ra';
    }  
}

export const getCookie = (name) => {
  const matches = document.cookie.match(new RegExp(
    '(?:^|; )' + name.replace(/([.$?*|{}()\[\]\\/+^])/g, '\\$1') + '=([^;]*)'
  ));
  return matches ? decodeURIComponent(matches[1]) : null;
};
