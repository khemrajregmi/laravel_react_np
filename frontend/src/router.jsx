import { createBrowserRouter } from 'react-router-dom';
import Login from './pages/Login';
import About from './pages/About';
import Profile from './pages/Profile';
import Register from './pages/Register';
import ProtectedLayout from './components/ProtectedLayout';
import GuestLayout from './components/GuestLayout';
import NewsFeed from "./pages/NewsFeed.jsx";
import PersonalizedNewsFeed from "./pages/PersonalizedNewsFeed.jsx";
import Preference from "./pages/PersonalizedNewsFeed.jsx";

const router = createBrowserRouter([
    {
        path: '/',
        element: <GuestLayout />,
        children: [
            {
                path: '/',
                element: <Login />,
            },
            {
                path: '/register',
                element: <Register />,
            },
        ],
    },
    {
        path: '/',
        element: <ProtectedLayout />,
        children: [
            {
                path: '/about',
                element: <About />,
            },
            {
                path: '/profile',
                element: <Profile />,
            },
            {
                path: '/news',
                element: <NewsFeed />,
            },
            {
                path: '/preference',
                element: <Preference />,
            }
        ],
    },
]);

export default router;
