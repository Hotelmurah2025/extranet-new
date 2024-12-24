import React, { Suspense } from 'react';
import type { DashboardMetrics } from '@/types/dashboard';

async function fetchDashboardMetrics(): Promise<DashboardMetrics> {
  const response = await fetch('http://localhost:3000/api/dashboard/metrics', {
    next: { revalidate: 300 }, // Revalidate every 5 minutes
  });
  
  if (!response.ok) {
    throw new Error('Failed to fetch dashboard metrics');
  }

  return response.json();
}

function LoadingWidget() {
  return (
    <div className="bg-white dark:bg-gray-800 rounded-lg shadow p-6 animate-pulse">
      <div className="h-4 bg-gray-200 dark:bg-gray-700 rounded w-3/4 mb-4"></div>
      <div className="h-8 bg-gray-200 dark:bg-gray-700 rounded w-1/2"></div>
    </div>
  );
}

function ErrorWidget({ message }: { message: string }) {
  return (
    <div className="bg-red-50 dark:bg-red-900/20 rounded-lg shadow p-6">
      <h3 className="text-lg font-semibold mb-2 text-red-700 dark:text-red-400">
        Error
      </h3>
      <p className="text-sm text-red-600 dark:text-red-300">{message}</p>
    </div>
  );
}

const DashboardPage = async () => {
  let metrics: DashboardMetrics;
  let error: Error | null = null;

  try {
    metrics = await fetchDashboardMetrics();
  } catch (e) {
    error = e as Error;
    metrics = {
      occupancy: 0,
      revenue: 0,
      totalBookings: 0,
    };
  }
  return (
    <div className="p-6">
      <h1 className="text-3xl font-bold mb-8">Dashboard</h1>
      
      {/* Key Metrics Section */}
      <div className="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <Suspense fallback={<LoadingWidget />}>
          {error ? (
            <ErrorWidget message={error.message} />
          ) : (
            <>
              {/* Occupancy Widget */}
              <div className="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h3 className="text-lg font-semibold mb-2 text-gray-700 dark:text-gray-200">
                  Occupancy Rate
                </h3>
                <p className="text-3xl font-bold text-blue-600 dark:text-blue-400">
                  {metrics.occupancy}%
                </p>
                <p className="text-sm text-gray-500 dark:text-gray-400 mt-2">
                  Current occupancy rate
                </p>
              </div>

              {/* Revenue Widget */}
              <div className="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h3 className="text-lg font-semibold mb-2 text-gray-700 dark:text-gray-200">
                  Revenue
                </h3>
                <p className="text-3xl font-bold text-green-600 dark:text-green-400">
                  ${metrics.revenue.toLocaleString()}
                </p>
                <p className="text-sm text-gray-500 dark:text-gray-400 mt-2">
                  Total revenue this month
                </p>
              </div>

              {/* Bookings Widget */}
              <div className="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h3 className="text-lg font-semibold mb-2 text-gray-700 dark:text-gray-200">
                  Total Bookings
                </h3>
                <p className="text-3xl font-bold text-purple-600 dark:text-purple-400">
                  {metrics.totalBookings}
                </p>
                <p className="text-sm text-gray-500 dark:text-gray-400 mt-2">
                  Bookings this month
                </p>
              </div>
            </>
          )}
        </Suspense>
      </div>

      {/* Quick Navigation Section */}
      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <QuickNavCard
          title="Manage Hotels"
          description="Add, edit, or remove hotel properties"
          href="/hotels"
          icon="🏨"
        />
        <QuickNavCard
          title="Room Management"
          description="Manage room types and availability"
          href="/rooms"
          icon="🛏️"
        />
        <QuickNavCard
          title="Rate Plans"
          description="Configure pricing and promotions"
          href="/rate-plans"
          icon="💰"
        />
        <QuickNavCard
          title="Availability"
          description="Update room availability"
          href="/availability"
          icon="📅"
        />
      </div>
    </div>
  );
};

interface QuickNavCardProps {
  title: string;
  description: string;
  href: string;
  icon: string;
}

const QuickNavCard = ({ title, description, href, icon }: QuickNavCardProps) => {
  return (
    <a
      href={href}
      className="block bg-white dark:bg-gray-800 rounded-lg shadow p-6 hover:shadow-lg transition-shadow duration-200"
    >
      <div className="text-3xl mb-4">{icon}</div>
      <h3 className="text-lg font-semibold mb-2 text-gray-700 dark:text-gray-200">
        {title}
      </h3>
      <p className="text-sm text-gray-500 dark:text-gray-400">{description}</p>
    </a>
  );
};

export default DashboardPage;
