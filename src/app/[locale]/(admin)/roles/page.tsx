import { Suspense } from 'react';
import type { Role } from '@/types/role';

async function fetchRoles(): Promise<Role[]> {
  const response = await fetch('http://localhost:3000/api/roles', {
    next: { revalidate: 300 }, // Revalidate every 5 minutes
  });
  
  if (!response.ok) {
    throw new Error('Failed to fetch roles');
  }

  return response.json();
}

function RoleCard({ role }: { role: Role }) {
  return (
    <div className="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
      <h3 className="text-lg font-semibold mb-4">{role.name}</h3>
      <div className="space-y-2">
        <h4 className="text-sm font-medium text-gray-500 dark:text-gray-400">Permissions:</h4>
        <ul className="list-disc list-inside space-y-1">
          {role.permissions.map((permission) => (
            <li key={permission.id} className="text-sm">
              {permission.name}
              <p className="ml-5 text-xs text-gray-500 dark:text-gray-400">
                {permission.description}
              </p>
            </li>
          ))}
        </ul>
      </div>
    </div>
  );
}

function LoadingCard() {
  return (
    <div className="bg-white dark:bg-gray-800 rounded-lg shadow p-6 animate-pulse">
      <div className="h-4 bg-gray-200 dark:bg-gray-700 rounded w-1/4 mb-4"></div>
      <div className="space-y-3">
        <div className="h-3 bg-gray-200 dark:bg-gray-700 rounded w-3/4"></div>
        <div className="h-3 bg-gray-200 dark:bg-gray-700 rounded w-5/6"></div>
        <div className="h-3 bg-gray-200 dark:bg-gray-700 rounded w-2/3"></div>
      </div>
    </div>
  );
}

async function RoleList() {
  const roles = await fetchRoles();

  return (
    <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
      {roles.map((role) => (
        <RoleCard key={role.id} role={role} />
      ))}
    </div>
  );
}

export default function RolesPage() {
  return (
    <div className="container mx-auto px-4 py-8">
      <div className="flex justify-between items-center mb-8">
        <h1 className="text-2xl font-bold">Role Management</h1>
        <button className="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
          Create Role
        </button>
      </div>
      <Suspense fallback={
        <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
          <LoadingCard />
          <LoadingCard />
        </div>
      }>
        <RoleList />
      </Suspense>
    </div>
  );
}
