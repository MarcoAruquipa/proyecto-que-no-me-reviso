import '../utils/parsers.dart';

class PaginatedResponse<T> {
  final List<T> data;
  final int currentPage;
  final int lastPage;
  final int total;
  final int perPage;

  PaginatedResponse({
    required this.data,
    required this.currentPage,
    required this.lastPage,
    required this.total,
    required this.perPage,
  });

  factory PaginatedResponse.fromJson(
    Map<String, dynamic> json,
    T Function(Map<String, dynamic>) fromJson,
  ) {
    return PaginatedResponse(
      data: (json['data'] as List).map((e) => fromJson(e)).toList(),
      currentPage: parseIntOr(json['current_page'], 1),
      lastPage: parseIntOr(json['last_page'], 1),
      total: parseIntOr(json['total'], 0),
      perPage: parseIntOr(json['per_page'], 15),
    );
  }

  bool get hasNextPage => currentPage < lastPage;
  bool get hasPrevPage => currentPage > 1;
}
