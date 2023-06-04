import React, { useState, useEffect } from 'react';
import axios from 'axios';
import {  useNavigate } from 'react-router-dom';

const Preference = () => {
    const navigate = useNavigate();
    const [categories, setCategories] = useState([
        { key: '', value: '' },
        { key: '1', value: 'Business' },
        { key: '2', value: 'Health' },
        { key: '3', value: 'Science' },
        { key: '4', value: 'Sports' },
        { key: '5', value: 'Technology' },
    ]);
    const [sources, setSources] = useState(['','NewsAPI', 'NYTimes', 'TheGuardian']);
    const [selectedCategory, setSelectedCategory] = useState('');
    const [selectedSource, setSelectedSource] = useState('');
    const [selectedAuthor, setSelectedAuthor] = useState('');

    useEffect(() => {
        // Fetch additional data if needed
    }, []);

    const handleSubmit = async (e) => {
        e.preventDefault();

        try {
            const response = await axios.post('http://localhost/api/preferences',
                {
                    source: selectedSource,
                    category_id: selectedCategory,
                    author: selectedAuthor,
                },
                {
                    withCredentials: true, // Send cookies including CSRF token
                    headers: {
                        'X-CSRF-TOKEN': window.csrfToken, // Set CSRF token header
                    },
                }
            );

            // Handle the API response as needed
            console.log('Preferences saved successfully:', response.data);
            navigate('/profile');


            // ...rest of the form submission logic
        } catch (error) {
            console.error('Error:', error);
            // Handle the error
        }
    };

    return (
        <>
            <div className="text-6xl font-bold text-slate-500">Hot News</div>
            <hr className="bg-slate-400 h-1 w-full my-4" />
            <div className="max-w-lg mx-auto">

                <form onSubmit={handleSubmit}>
                    <div className="mb-4">
                        <label htmlFor="category" className="block text-gray-700 font-medium mb-2">
                            Category
                        </label>
                        <select
                            id="category"
                            className="w-full border border-gray-300 rounded-md py-2 px-3 focus:outline-none focus:ring focus:ring-indigo-200"
                            value={selectedCategory}
                            onChange={(e) => setSelectedCategory(e.target.value)}
                        >
                            {categories.map((category) => (
                                <option key={category.key} value={category.key}>
                                    {category.value}
                                </option>
                            ))}
                        </select>

                    </div>

                    <div className="mb-4">
                        <label htmlFor="source" className="block text-gray-700 font-medium mb-2">
                            Source
                        </label>
                        <select
                            id="source"
                            className="w-full border border-gray-300 rounded-md py-2 px-3 focus:outline-none focus:ring focus:ring-indigo-200"
                            value={selectedSource}
                            onChange={(e) => setSelectedSource(e.target.value)}
                        >
                            {sources.map((source) => (
                                <option key={source} value={source}>
                                    {source}
                                </option>
                            ))}
                        </select>
                    </div>

                    <div className="mb-4">
                        <label htmlFor="author" className="block text-gray-700 font-medium mb-2">
                            Author
                        </label>
                        <input
                            type="text"
                            id="author"
                            className="w-full border border-gray-300 rounded-md py-2 px-3 focus:outline-none focus:ring focus:ring-indigo-200"
                            value={selectedAuthor}
                            onChange={(e) => setSelectedAuthor(e.target.value)}
                        />
                    </div>

                    <button
                        type="submit"
                        className="bg-indigo-500 text-white py-2 px-4 rounded-md hover:bg-indigo-600"
                    >
                        Save Preference
                    </button>
                </form>
            </div>
        </>
    );

};

export default Preference;
