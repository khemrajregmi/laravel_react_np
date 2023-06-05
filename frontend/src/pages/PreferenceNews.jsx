import React, { useState, useEffect } from 'react';
import axios from 'axios';
import { useAuth } from '../contexts/AuthContext';
import DatePicker from 'react-datepicker';
import 'react-datepicker/dist/react-datepicker.css';

const PreferenceNews = () => {
    const [data, setData] = useState([]);
    const [visibleItems, setVisibleItems] = useState(5);
    const [isLoading, setIsLoading] = useState(false);
    const { user } = useAuth();
    const userId = user.id; // Assuming `user.id` contains the user ID


    useEffect(() => {
        fetchData().then(r => {});
    }, []);

    const fetchPreference = async () => {
        try {
            const headers = {
                withCredentials: true,
                'X-CSRF-TOKEN': window.csrfToken,
            };
            const userId = user.id; // Assuming `user.id` contains the user ID
            console.log(userId, window.csrfToken);
            const response = await axios.get(`http://localhost/api/preference-detail/${userId}`, {
                headers,
            });
            const preferenceData = response.data;
        } catch (error) {
            console.error(error);
        }
    };

    const fetchData = async () => {

        try {
            setIsLoading(true);
            const response = await axios.get(`http://localhost/api/feed/${userId}`);
            const { data: responseData } = response.data;
            setData(responseData);
            setVisibleItems(6);
            setIsLoading(false);
        } catch (error) {
            console.error(error);
        }
    };

    const handleViewMore = async () => {
        setIsLoading(true);
        const response = await axios.get(`http://localhost/api/feed/${userId}`, {
            params: {
                page: Math.ceil(visibleItems / 6) + 1,
                per_page: 6,
            },
        });
        const { data: newItems } = response.data;
        setData((prevData) => [...prevData, ...newItems]);
        setVisibleItems((prevVisibleItems) => prevVisibleItems + 6);
        setIsLoading(false);
    };

    return (
        <>
            <div className="text-3xl font-bold text-slate-500">Your Preference News</div>
            <hr className="bg-slate-400 h-1 w-full my-4" />

            <ul className="grid grid-cols-1 md:grid-cols-3 gap-4">
                {data.slice(0, visibleItems).map((item) => (
                    <li key={item.id}>
                        <a href={item.url} target="_blank" rel="noopener noreferrer">
                            <div className="p-6 bg-white border border-gray-200 shadow-xl rounded-lg">
                                <h5 className="my-2 text-2xl font-bold tracking-tight">
                                    {item.title}
                                </h5>
                                <p className="font-normal text-gray-700">Category: {item.category.name}</p>
                                {item.source ? (
                                    <p className="font-normal text-gray-700">
                                        Source:{' '}
                                        <span className="text-green-500">{item.source}</span>
                                    </p>
                                ) : (
                                    <p className="font-normal text-gray-700">
                                        Source: <span className="text-red-500">Not available</span>
                                    </p>
                                )}
                            </div>
                        </a>
                    </li>
                ))}
            </ul>
            {data.length > visibleItems && (
                <button
                    onClick={handleViewMore}
                    className="px-4 py-2 my-4 text-sm font-medium text-white bg-indigo-500 rounded-md hover:bg-indigo-600"
                    disabled={isLoading}
                >
                    {isLoading ? 'Loading...' : 'View More'}
                </button>
            )}
        </>
    );
};

export default PreferenceNews;
