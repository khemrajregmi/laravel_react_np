import React, { useState, useEffect } from 'react';
import axios from 'axios';
import { useAuth } from '../contexts/AuthContext';
import DatePicker from 'react-datepicker';
import 'react-datepicker/dist/react-datepicker.css';

const Feed = () => {
    const [data, setData] = useState([]);
    const [visibleItems, setVisibleItems] = useState(5);
    const [isLoading, setIsLoading] = useState(false);
    const [filter, setFilter] = useState({
        date: null,
        category: null,
        source: null,
    });

    const { user } = useAuth();

    useEffect(() => {
        fetchData().then(r => {});
    }, [filter]);

    const fetchData = async () => {
        try {
            setIsLoading(true);
            const response = await axios.get('http://localhost/api/feed', {
                params: filter,
            });
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
        const response = await axios.get('http://localhost/api/feed', {
            params: {
                ...filter,
                page: Math.ceil(visibleItems / 6) + 1,
                per_page: 6,
            },
        });
        const { data: newItems } = response.data;
        setData((prevData) => [...prevData, ...newItems]);
        setVisibleItems((prevVisibleItems) => prevVisibleItems + 6);
        setIsLoading(false);
    };

    const handleFilterChange = (e) => {
        setFilter({ ...filter, [e.target.name]: e.target.value });
    };

    return (
        <>
            <div className="text-6xl font-bold text-slate-500">Hot News</div>
            <hr className="bg-slate-400 h-1 w-full my-4" />

            <div className="flex items-center justify-center mb-4 space-x-4">
                <DatePicker
                    selected={filter.date}
                    onChange={(date) => setFilter({ ...filter, date })}
                    placeholderText="Select Date"
                    className="px-4 py-2 border border-gray-300 rounded"
                />
                <select
                    name="category"
                    value={filter.category}
                    onChange={handleFilterChange}
                    className="px-4 py-2 border border-gray-300 rounded"
                >
                    <option value="">Select Category</option>
                    <option value="1">Business</option>
                    <option value="2">Health</option>
                    <option value="3">Science</option>
                    <option value="4">Sports</option>
                    <option value="5">Technology</option>
                </select>
                <select
                    name="source"
                    value={filter.source}
                    onChange={handleFilterChange}
                    className="px-4 py-2 border border-gray-300 rounded"
                >
                    <option value="">Select Source</option>
                    <option value="NewsAPI">News API</option>
                    <option value="NYTimes">The New York Times</option>
                    <option value="TheGuardian">The Guardian</option>
                </select>
                <button
                    className="px-4 py-2 text-sm font-medium text-white bg-indigo-500 rounded-md hover:bg-indigo-600"
                    onClick={fetchData}
                >
                    Apply Filter
                </button>
            </div>

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

export default Feed;
