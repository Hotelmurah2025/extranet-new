<<<<<<< HEAD
export interface User {
  id: string;
  name: string;
  email: string;
  role: 'HOTEL_OWNER' | 'SUPER_ADMIN';
  createdAt: Date;
  updatedAt: Date;
}

export interface LoginCredentials {
  email: string;
  password: string;
}

export interface RegisterData {
  name: string;
  email: string;
  password: string;
  role: 'HOTEL_OWNER';
}

export interface ForgotPasswordData {
  email: string;
}

export interface ResetPasswordData {
  token: string;
  password: string;
}

export interface AuthResponse {
  user: User;
  token: string;
}
||||||| parent of b74a550 (feat: implement authentication system)
=======
export interface LoginCredentials {
  email: string;
  password: string;
}

export interface RegisterData extends LoginCredentials {
  name: string;
  role: 'HOTEL_OWNER' | 'SUPER_ADMIN';
}

export interface AuthResponse {
  token: string;
  user: {
    id: string;
    name: string;
    email: string;
    role: string;
  };
}

export interface ForgotPasswordData {
  email: string;
}
>>>>>>> b74a550 (feat: implement authentication system)
