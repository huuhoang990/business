import React from 'react';
import { useSelector } from 'react-redux';
import { RootState } from '@/Store';

const LoadingSpinner : React.FC = () => {
  const isLoading = useSelector((state: RootState) => state.loading.isLoading);

  if (!isLoading) return null;

  return (
    <div className="loader-overlay">
      <div className="popup-spinner">
        <div className="spinner-border text-light"></div>
      </div>
    </div>
  );
};

export default LoadingSpinner;
