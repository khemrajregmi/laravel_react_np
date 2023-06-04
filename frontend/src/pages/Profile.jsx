import React, { useState, useEffect } from 'react';
import { useAuth } from '../contexts/AuthContext';
import axios from 'axios';

export default function Profile() {
    const { user } = useAuth();
    const [preference, setPreference] = useState(null);

    useEffect(() => {
        fetchPreference().then(r => {});
    }, []);

    const fetchPreference = async () => {
        try {
            const response = await axios.get('http://localhost/api/preferences');
            const preferenceData = response.data;
            setPreference(preferenceData);
        } catch (error) {
            console.error(error);
        }
    };

    return (
        <>
            <div className="text-6xl font-bold text-slate-500">Hot News</div>
            <hr className="bg-slate-400 h-1 w-full my-4" />

            <div className="grid grid-cols-2 gap-4">
                <div className="block p-10 bg-white border border-gray-200 shadow-xl rounded-lg shadowdark:border-gray-700">
                    <p className="font-bold italic text-blue-500">Your Profile</p>
                    <h6 className="my-2 text-2xl font-bold tracking-tight">
                        Name: {user.name}
                    </h6>
                    <p className="font-normal text-gray-700">Email: {user.email}</p>
                </div>

                <div className="block p-10 bg-white border border-gray-200 shadow-xl rounded-lg shadowdark:border-gray-700">
                    <p className="font-bold italic text-blue-500">Your Preferences</p>
                    {preference ? (
                        <>
                            <h6 className="my-2 text-2xl font-bold tracking-tight">
                                Category: {preference.category}
                            </h6>
                            <p className="font-normal text-gray-700">
                                Source: {preference.source}
                            </p>
                            <p className="font-normal text-gray-700">
                                Author: {preference.author}
                            </p>
                        </>
                    ) : (
                        <p>No preferences found.</p>
                    )}
                </div>
            </div>
        </>
    );
}
