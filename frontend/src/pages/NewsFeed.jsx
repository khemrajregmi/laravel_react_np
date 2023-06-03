import React, { useState, useEffect } from 'react';
import axios from 'axios';
import { useAuth } from '../contexts/AuthContext';

const Feed = () => {
    const [data, setData] = useState([]);
    const [visibleItems, setVisibleItems] = useState(5);
    const [isLoading, setIsLoading] = useState(false);
    const { user } = useAuth();

    useEffect(() => {
        fetchData();
    }, []);

    const fetchData = async () => {
        try {
            setIsLoading(true);
            const response = await axios.get('http://localhost/api/feed');
            const { data: responseData } = response.data;
            setData(responseData);
            setIsLoading(false);
        } catch (error) {
            console.error(error);
        }
    };

    const handleViewMore = () => {
        setVisibleItems((prevVisibleItems) => prevVisibleItems + 5);
    };

    return (
        <>
            <div className="text-6xl font-bold text-slate-500">Hot News</div>
            <hr className="bg-slate-400 h-1 w-full my-4" />
            <div className="block p-10 bg-white border border-gray-200 shadow-xl rounded-lg shadowdark:border-gray-700">
                <h5 className="my-2 text-2xl font-bold tracking-tight">Name: {user.name}</h5>
                <p className="font-normal text-gray-700">Email: {user.email}</p>
                <p className="font-normal text-gray-700">Created At: {user.created_at}</p>
            </div>
            <ul>
                {data.slice(0, visibleItems).map((item) => (
                    <li key={item.id}>
                        <div className="bg-white shadow-md rounded-lg p-6 my-4">
                            <h3 className="text-xl font-bold mb-2">{item.title}</h3>
                            {/* Additional content */}
                        </div>
                    </li>
                ))}
            </ul>
            {visibleItems < data.length && (
                <button
                    onClick={handleViewMore}
                    className="px-4 py-2 my-4 text-sm font-medium text-white bg-indigo-500 rounded-md hover:bg-indigo-600"
                >
                    {isLoading ? 'Loading...' : 'View More'}
                </button>
            )}
        </>
    );
};

export default NewsFeed;
